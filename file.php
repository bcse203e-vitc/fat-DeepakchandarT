<?php

function normalizeText($filename, $mode) {
    $corrections = 0;
    $punctuationLines = [];

    $lines = file($fileName, FILE_IGNORE_NEW_LINES);

    $normalized = [];
    foreach ($lines as $i => $line) {
        $new = preg_replace('/[ \t]+/', ' ', $line);
        if ($new !== $line) $corrections++;
        $line = $new;

        $trimmed = trim($line);
        if ($trimmed !== $line) $corrections++;
        $line = $trimmed;

        if ($line !== "" && preg_match('/^[[:punct:]]+$/', $line)) {
            $punctuationLines[] = $i + 1;
        }

        $normalized[] = $line;
    }

    if ($mode === "compress") {
        $result = [];
        $blank = false;
        foreach ($normalized as $line) {
            if ($line === "") {
                if (!$blank) $result[] = "";
                $blank = true;
            } else {
                $result[] = $line;
                $blank = false;
            }
        }
        $normalized = $result;
    }

    if ($mode === "expand") {
        $result = [];
        foreach ($normalized as $line) {
            $result[] = $line;
            $result[] = "";
        }
        $normalized = $result;
    }

    file_put_contents($fileName, implode("\n", $normalized) . "\n");

    if (!empty($punctuationLines)) {
        echo "Lines containing only punctuation: " . implode(", ", $punctuationLines) . "\n";
    }

    return $corrections;
}

?>


