<?php
// ### ПРОЦЕДУРНЫЙ МЕТОД ###

$day = "16"; // день первого месяца, с которого начинаем подсчёт рабочих дней
$month = "10";  // месяц, с которого начинаем подсчёт рабочих дней
$year = "2014"; // год, с которого начинаем подсчёт рабочих дней
$quantityMonths = 4;  // количество месяцев, за которые считаем рабочие дни

$restPeriod = 2;  // период отдыха между рабочими днями

function daysOfCalendar($day, $month, $year, $quantityMonths): array { // функция формирования списка всех дней календаря в указанном диапазоне

    if (    // проверка входных данных функции (кроме года)
        (int)$day <= 0 ||
        (int)$month > 12 ||
        (int)$month <= 0
    ) {
        fwrite(STDERR,"ERROR! Incoming values out of range!\n");
        return [];
    }

    $start_date = $day."-".$month."-".$year;
    $start_time = strtotime($start_date);
    $startMonth = date('m', $start_time);

    $end_time = strtotime("+$quantityMonths month", $start_time);

    $counter = 1;
    $temporalValueMonth = $startMonth;

    for($i=$start_time; $i<$end_time; $i+=24*60*60)
    {
        $currentMonth = date('m', $i);

        if ($currentMonth !== $temporalValueMonth) {
            $temporalValueMonth = $currentMonth;
            $counter += 1;
        }

        if ($counter > $quantityMonths) break;

        $list[] = date('Y-m-d-D', $i);

    }
    return $list;
}

// print_r(daysOfCalendar($day, $month, $year, $quantityMonths));

function workDaysPrint($items, $restPeriod): void { // ФУНКЦИЯ ПЕРЕБОРА СПИСКА и вывода его содержимого

    $periodCounter = 0;

    echo PHP_EOL;
    echo "***<начало списка>*** \n";

    foreach ( $items as $item ) {

        if (substr($item, -3, 3) === 'Sat' || substr($item, -3, 3) === 'Sun') // поиск выходных дней Weekend-а в общем списке
        {
            $fullElement = ((int)array_search($item, $items) + 1) . '.' . $item;
            echo "\033[32m $fullElement \033[0m" . PHP_EOL; // выходные дни Weekend-а (помечается ЗЕЛЁНЫМ)
        } else {
            if ($periodCounter === 0) {
                $fullElement = ((int)array_search($item, $items) + 1) . '.' . $item;
                echo "\033[31m $fullElement \033[0m" . PHP_EOL; // рабочий день (помечается КРАСНЫМ)

            } else {
                echo ' ' . ((int)array_search($item, $items) + 1) . '.' . $item . PHP_EOL; // выходной день, не попадающий на субботу и воскресенье
            }
            $periodCounter += 1;
            if ($periodCounter === $restPeriod + 1) {
                $periodCounter = 0;
            }
        }
    }
    echo "***<конец списка>*** \n";
}

workDaysPrint(daysOfCalendar($day, $month, $year, $quantityMonths), $restPeriod);