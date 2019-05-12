<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {

    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Преобразует специальные символы в HTML сущности
 * @param string $str HTML-код
 * @return string Итоговый HTML-код
 */
function esc($str) {
	return htmlspecialchars($str);
}

/**
 * Округлет цену и производит разделение пробелами групп цифр по 3 цифры
 * @param int $price
 * @return string Итоговый HTML-код
 */
function get_str_price($price) {
    $result_str = $price;
    if ($price >= 1000) {
        $result_str = esc(number_format(ceil($price), 0, ',', ' '));
    }
    return $result_str;
}

/**
 * Возвращает временной период до окончания жизни лота
 * @param string $finish_date Дата в виде строки
 * @return string $time_count временной период в виде ЧЧЧЧ:ММ
 */
function get_time_count($finish_date) {
    $current_date_timestamp = time();
    $secs_diff = strtotime($finish_date) - $current_date_timestamp;
    $hours = floor($secs_diff / 3600);
    $minutes = floor(($secs_diff % 3600) / 60);
    $time_count = $hours.':'.$minutes;

    return $time_count;
}

/**
 * Возвращает признак срока финиша лота менее 1 часа
 * @param string $finish_date Дата в виде строки
 * @return bool
 */
function get_timer_finishing($finish_date) {
    $finish_time_period = get_time_count($finish_date);
    $finish_time_array = explode(":", $finish_time_period);
    if ($finish_time_array[0] < 1) {
        return true;
    }

    return false;
}

/**
 * Получает по заданному sql-запросу sql-записи в виде двумерного массива
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return array $result массив полученных sql-записей
 */
function db_fetch_data($link, $sql, $data = []) {
    $result = [];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    return $result;
}

/**
 * Создает sql-запись по заданному sql-запросу
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return int $result ID, сгенерированный для добавленной sql-записи
 */
function db_insert_data($link, $sql, $data = []) {
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
        $result = mysqli_insert_id($link);
    }

    return $result;
}

/**
 * Устанавливает код ошибки сервера и отображает соответствующий данной ошибке блок
 * @param int $code  Номер кода ошибки сервера
 * @param array $categories Перечень всех категорий товаров
 * @param string $title Заголовок страницы
 */
function display_error_code_block ($code, $categories, $title) {
    http_response_code($code);
    $page_content = include_template($code . '.php');

    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'user' => $user,
        'categories' => $categories,
        'title' => $title
    ]);

    print($layout_content);
}

/**
 * Переводит временной период в человекочитаемый вид (5 минут назад, 2 часа назад, вчера в ..., и т.д.)
 * @param int $time Временная unix-метка
 *
 * @return string Результирующая строка
 */
function showDate($time) {
    $period = time() - $time;

    if (($time >= strtotime('yesterday')) && ($time < strtotime('today'))) {
        return 'Вчера, в ' . date('H:i', $time);
    }
    if ($period < 60) {
        return 'меньше минуты назад';
    } elseif ($period < 3600) {
        return intval($period/60) . ' ' . get_noun_plural_form($period/60, 'минута', 'минуты', 'минут') . ' назад';
    } elseif ($period < 86400) {
        return intval($period/3600) . ' ' . get_noun_plural_form($period/3600, 'час', 'часа', 'часов') . ' назад';
    }

    return date('d.m.y', $time) . ' в ' . date('H:i', $time);
}
