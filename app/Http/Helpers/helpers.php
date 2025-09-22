<?php
// Money Format: LKR
function lkr_money_format($number)
{
    $num = (float) $number; // cast string/other to float
    return 'Rs. ' . number_format($num, 2, '.', ',');
}

//I was change it you can use this function for invoice to read number
//if you want Sinhala/Tamil/LKR words, let me know and I’ll rewrite)
function number_in_words($number)
{
    $units = ["", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine"];
    $tens = ["", "", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety"];
    $teens = ["ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen"];

    if ($number == 0) return "zero rupees";
    if ($number < 0) return "minus " . number_in_words(abs($number));

    // Separate rupees and cents
    $rupees = floor($number);
    $cents = round(($number - $rupees) * 100);

    $result = "";

    // Process rupees
    if ($rupees >= 5000) {
        $result .= number_in_words(floor($rupees / 5000)) . " thousand ";
        $rupees %= 5000;
    }

    if ($rupees >= 1000) {
        $result .= number_in_words(floor($rupees / 1000)) . " thousand ";
        $rupees %= 1000;
    }

    if ($rupees >= 500) {
        $result .= number_in_words(floor($rupees / 500)) . " five hundred ";
        $rupees %= 500;
    }

    if ($rupees >= 100) {
        $result .= number_in_words(floor($rupees / 100)) . " hundred ";
        $rupees %= 100;
    }

    if ($rupees >= 20) {
        $result .= $tens[floor($rupees / 10)] . " ";
        $rupees %= 10;
    } elseif ($rupees >= 10) {
        $result .= $teens[$rupees - 10] . " ";
        $rupees = 0;
    }

    if ($rupees > 0) {
        $result .= $units[$rupees] . " ";
    }

    $result = trim($result) . " rupees";

    // Process cents if present
    if ($cents > 0) {
        $cent_words = "";

        if ($cents >= 20) {
            $cent_words .= $tens[floor($cents / 10)] . " ";
            $cents %= 10;
        } elseif ($cents >= 10) {
            $cent_words .= $teens[$cents - 10] . " ";
            $cents = 0;
        }

        if ($cents > 0) {
            $cent_words .= $units[$cents] . " ";
        }

        $result .= " and " . trim($cent_words) . " cents";
    }

    return $result;
}


// Sri Lankan Date Format
function lkr_date($dt, $day_show = true)
{
    $day_name = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    $month_name = [1 => "January", "February", "March", "April", "May", "June",
                   "July", "August", "September", "October", "November", "December"];

    $dt_obj = new DateTime($dt);
    $day = $day_show ? $day_name[$dt_obj->format('w')] . ', ' : '';
    $formattedDate = $dt_obj->format('d') . ' ' . $month_name[(int)$dt_obj->format('m')] . ' ' . $dt_obj->format('Y');
    $time = $dt_obj->format('h:i A'); // 12-hour with AM/PM

    return $day . $formattedDate . ', ' . $time;
}

//
function code_generator($val, $threshold = null) {
    return sprintf("%0" . $threshold . "s", $val);
}
