<?php

namespace Classes;

use Exception;
use Model\Tarea;
use Phpml\Classification\KNearestNeighbors;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\ModelManager;
use Phpml\Tokenization\WhitespaceTokenizer;

class PrioridadML
{
    protected static $modeloPath = __DIR__ . '/../modelo_tareas.model';

    public static function entrenar()
    {
        $tareas = Tarea::joinWhere([], [['prioridad', '', '!=']], null, 'titulo, descripcion, prioridad');

        $samples = [];
        $labels = [];

        foreach ($tareas as $tarea) {
            $prioridad = trim($tarea->prioridad ?? '');
            $titulo = trim($tarea->titulo ?? '');

            if ($prioridad === '' || $titulo === '') {
                continue;
            }

            $descripcion = trim($tarea->descripcion ?? '');
            $texto = strtolower($titulo . ' ' . $descripcion);

            $samples[] = $texto;  // Debe ser string plano, NO tokenizado
            $labels[] = $prioridad;
        }

        if (count($samples) !== count($labels)) {
            throw new Exception('Error: samples y labels tienen diferente tamaño: ' . count($samples) . ' vs ' . count($labels));
        }

        if (count($samples) === 0) {
            throw new Exception('Error: No hay datos para entrenar el modelo');
        }

        // Vectorizador y tokenizador
        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
        $vectorizer->fit($samples);
        $vectorizer->transform($samples);

        $classifier = new KNearestNeighbors();
        $classifier->train($samples, $labels);

        $modelManager = new ModelManager();

        // Guardar vectorizador y clasificador en archivos separados
        file_put_contents(__DIR__ . '/../vectorizer.model', serialize($vectorizer));
        $modelManager->saveToFile($classifier, __DIR__ . '/../classifier.model');
    }

    public static function clasificarPrioridad(string $texto)
    {
        $modelManager = new ModelManager();

        $vectorizerPath = __DIR__ . '/../vectorizer.model';
        $classifierPath = __DIR__ . '/../classifier.model';

        if (!file_exists($vectorizerPath) || !file_exists($classifierPath)) {
            self::entrenar();
        }

        $vectorizer = unserialize(file_get_contents(__DIR__ . '/../vectorizer.model'));

        $classifier = $modelManager->restoreFromFile($classifierPath);

        $samples = [strtolower($texto)];
        $vectorizer->transform($samples);

        return $classifier->predict($samples);
    }

    protected static function tokenizar(string $texto)
    {
        $texto = strtolower($texto);
        $tokens = preg_split('/\W+/', $texto, -1, PREG_SPLIT_NO_EMPTY);
        return $tokens;
    }
}