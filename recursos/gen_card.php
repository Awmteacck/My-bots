<?php





function MultiExplodea($delimiters, $string)
    {
        $one = str_replace($delimiters, $delimiters[0], $string);
        $two = explode($delimiters[0], $one);
        return $two;
    }


function CCs($bin, $mes, $ano, $cvv)
{
    // Define un array con los signos que quieres usar
    $signos = ['[⫵]', '[⩸]', '[▣]', '[₾]', '[※]', '[♪]', '[⁜]', '[⩝]', '[Ϙ]', '[◌]', '[₡]', '[₤]', '[؋]', '[₺]', '[₻]', '[∵]', '[∴]', '[∺]', '[⊱]', '[⋙]', '[⨭]', '[⨮]', '[⨘]', '[⨚]', '[⩐]', '[⩍]', '[⩊]', '[⩲]', '[⩟]', '[⪤]', '[⪧]', '[⪮]', '[⪾]', '[⪿]', '[⫧]', '[⫝]', '[⫚]', '[⫝̸]', '[⫯]'];

    if (!is_numeric(substr($bin, 0, 6))) {
        return "Invalid Format, Check Your Input";
    } else {
        $cc = []; // Inicializa el array donde se almacenarán las líneas de CC
        for ($i = 1; $i <= 10; $i++) {
            $ccnum = gerarCC($bin); // Genera un nuevo número de tarjeta de crédito con el bin
            gerarCcMes($mes);
            gerarCcAno($ano);
            gerarCcCvv($cvv);
            $signo_aleatorio = $signos[array_rand($signos)]; // Escoge un signo aleatorio del array
            $cc[$i] = "$signo_aleatorio <code>".$GLOBALS["ccnum"] ."|".$GLOBALS["month"]."|".$GLOBALS["year"]."|".$GLOBALS["cvv"]."</code>";
        }
        return implode("\n", $cc); // Une las líneas de CC con saltos de línea
    }
}



function gerarCC($bin)
{
    if (substr_compare($bin, 37, 0, 2)) {
        $ccbin = preg_replace("/[^0-9x]/", "", substr($bin, 0, 15));
        for ($i = 0; $i < strlen($ccbin); $i++) {
            if ($ccbin[$i] == "x") {
                $ccbin[$i] = mt_rand(0, 9);
            }
        }
        $GLOBALS["ccnum"] = ccgen_number($ccbin, 16);
    } else {
        $ccbin = preg_replace("/[^0-9x]/", "", substr($bin, 0, 14));
        for ($i = 0; $i < strlen($ccbin); $i++) {
            if ($ccbin[$i] == "x") {
                $ccbin[$i] = mt_rand(0, 9);
            }
        }
        $GLOBALS["ccnum"] = ccgen_number($ccbin, 15);
    }
}

function gerarCcCvv($cvv)
{
    if (!empty($cvv)) {
        $GLOBALS["cvv"] = $cvv;
    } elseif (substr_compare($GLOBALS["bin"], 37, 0, 2)) {
        $GLOBALS["cvv"] = mt_rand(112, 998);
    } else {
        $GLOBALS["cvv"] = mt_rand(1102, 9998);
    }
}

function gerarCcMes($mes)
{
    if (!empty($mes)) {
        $GLOBALS["month"] = $mes;
    } else {
        $moth             = mt_rand(1, 12);
        $GLOBALS["month"] = (($moth < 10) ? '0' . $moth : $moth);
    }
}

function gerarCcAno($ano)
{
    if (!empty($ano)) {
        if (strlen($ano) == 2) {
            $GLOBALS["year"] = "20".$ano;
        } else {
            $GLOBALS["year"] = $ano;
        }
    } else {
        $GLOBALS["year"] = mt_rand(2023, 2031);
    }
}

function ccgen_number($prefix, $length)
{
    $ccnumber = $prefix;
    while (strlen($ccnumber) < ($length - 1)) {
        $ccnumber .= mt_rand(0, 9);
    }
    $sum              = 0;
    $pos              = 0;
    $reversedCCnumber = strrev($ccnumber);
    while ($pos < $length - 1) {
        $odd = $reversedCCnumber[$pos] * 2;
        if ($odd > 9) {
            $odd -= 9;
        }
        $sum += $odd;
        if ($pos != ($length - 2)) {
            $sum += $reversedCCnumber[$pos + 1];
        }
        $pos += 2;
    }
    $checkdigit = ((floor($sum / 10) + 1) * 10 - $sum) % 10;
    $ccnumber .= $checkdigit;
    return $ccnumber;
}