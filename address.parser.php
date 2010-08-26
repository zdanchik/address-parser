<?php

define('REGION'     ,1);  // Регион
define('CITY'       ,2);  // Город
define('AREA'       ,3);  // Район
define('MICRODIRECT',4);  // Микрорайон
define('STREET'     ,5);  // Улица
define('ALLEY'      ,6);  // Переулок
define('AVENUE'     ,7);  // Проспект
define('ROAD'       ,8);  // Шоссе
define('SQUARE', 9);    // Площадь
define('PLACE'  , 10);  // Дом
define('VILLAGE', 11); // Деревня
define('EMBANKMENT', 12); // Набережная
define('LOCALITY', 13);   // Населенный пункт (не определен парсером тип)
define('KM'       ,14);  // Киллометр
define('PASSAGE', 15);  // Проезд
define('BLVD', 16); // Бульвар
define('LINE' ,17);  // Линия
define('BUILDING', 18); // Строение
define('LEVEL', 19); // Этаж
define('POSSESSION', 20); // Владения
define('HOUSING', 21);  // Корпус
define('PORCH', 22);    // Подъезд
define('OFFICE', 23);   // Оффис
define('COMPANY_LOCALITY', 24);

/**
 * На вход должна подаваться строка вида: "[CITY/ REGION], [и далее по низходящей]"
 * Прим: (Москва, Петровка ул., 10, линия 1, этаж 1)
 */
function addressparser($companyAddr)
{
    $addrArray = explode(',', $companyAddr);

    $companyAddressParsed = array();
    foreach ($addrArray as $i => $addrItem)
    {
        switch($i)
        {
            /**
             * Возможные значения: Город? Область? Индекс?
             *
             * [Варианты не найдены - название населенного пункта] | обл. | г.
             */
            case 0:
                if (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'),  ' г.')
                   || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'г. ')
                   || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' город'))
                {
                    $companyAddressParsed[CITY] = trim(str_ireplace(array('г.','город'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' обл.')
                   || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'обл. ')
                   || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'область'))
                {
                    $companyAddressParsed[REGION] = trim(str_ireplace(array('обл.','область'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'р-н')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'район'))
                {
                    $companyAddressParsed[AREA] = trim(str_ireplace(array('р-н','район'), '', $addrItem));
                }
                else
                {
                    /**
                     * Ниче не подошло. Ну чтож.. Значит это какой то город =)
                     */
                    $companyAddressParsed[CITY] = trim($addrItem);
                }



                break;



            /**
             * | г | ок
             * | пр-д | ok
             * | ул | ok
             * | пер   | ок
             * | просп | ok
             * | пр-т | ok
             * | проспект | ok
             * | ш. | ok
             * | р-н | ok
             * | мкр. | ok
             * | пл.  | ok
             * | дер. | ok
             * | наб. | ok
             * | [Варианты не найдены - название населенного пункта] | ok
             * | бул. | ok
             */
            case 1:
                if (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' ул.')
                   || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'ул. ')
                   || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'улица'))
                {
                    $companyAddressParsed[STREET] = trim(str_ireplace(array('ул.','улица'), '', $addrItem));
                }
                elseif ( mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' г.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'г. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' город'))
                {
                    $companyAddressParsed[CITY] = trim(str_ireplace(array('г.','город'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'пр-д')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'проезд'))
                {
                    $companyAddressParsed[PASSAGE] = trim(str_ireplace(array('пр-д','проезд'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'пер. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' пер.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'переулок'))
                {
                    $companyAddressParsed[ALLEY] = trim(str_ireplace(array('пер.','переулок'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'просп. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' просп.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'пр-т')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'проспект'))
                {
                    $companyAddressParsed[AVENUE] = trim(str_ireplace(array('просп.','проспект', 'пр-т'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'ш. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' ш.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'шоссе'))
                {
                    $companyAddressParsed[ROAD] = trim(str_ireplace(array('ш.','шоссе'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'р-н')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'район'))
                {
                    $companyAddressParsed[AREA] = trim(str_ireplace(array('р-н','район'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' мкр.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'мкр. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'мкр-н')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'микрорайон'))
                {
                    $companyAddressParsed[AREA] = trim(str_ireplace(array('мкр.','мкр-н','микрорайон'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' пл.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'пл. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'площадь'))
                {
                    $companyAddressParsed[SQUARE] = trim(str_ireplace(array('пл.','площадь'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' дер.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'дер. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'пос. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' пос.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'поселок')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'деревня'))
                {
                    $companyAddressParsed[VILLAGE] = trim(str_ireplace(array('дер.','деревня'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' наб.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'наб. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'набережная'))
                {
                    $companyAddressParsed[EMBANKMENT] = trim(str_ireplace(array('наб.','набережная'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' бул.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'бул. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'бульвар'))
                {
                    $companyAddressParsed[BLVD] = trim(str_ireplace(array('бул.','бульвар'), '', $addrItem));
                }
                else
                {
                    $companyAddressParsed[LOCALITY] = trim($addrItem);
                }

                break;



            /**
             * | дер. | ok
             * | пос. | ok
             * | км. | ok
             * | вл. | ok
             * | шоссе | ok
             * | просп. | ok
             * | [Цифра (дом)] | ok
             * | [Цифра+буква (9А | 1/15 - нужен прег-мач) ]
             * | [Варианты не найдены - название организации]
             * | ул. | ok
             * | линия | ok
             * | стр. | ok
             * | этаж | ok
             * | эт.  | ok
             * | корп. | ok
             * | подъезд | ok
             * | офис | ok
             */
            default:

                if (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' ул.')
                   || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'ул. ')
                   || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'улица'))
                {
                    $companyAddressParsed[STREET] = trim(str_ireplace(array('ул.','улица'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' дер.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'дер. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'пос. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' пос.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'поселок')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'деревня'))
                {
                    $companyAddressParsed[VILLAGE] = trim(str_ireplace(array('дер.','деревня'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' км.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'км. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' км')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'км ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'километр'))
                {
                    $companyAddressParsed[KM] = trim(str_ireplace(array('км.','километр'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'ш. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' ш.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'шоссе'))
                {
                    $companyAddressParsed[ROAD] = trim(str_ireplace(array('ш.','шоссе'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'просп. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' просп.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'пр-т')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'проспект'))
                {
                    $companyAddressParsed[AVENUE] = trim(str_ireplace(array('просп.','проспект', 'пр-т'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'линия')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' лн.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'лн. '))
                {
                    $companyAddressParsed[LINE] = trim(str_ireplace(array('линия.','лн.'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'стр. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' стр.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'строение'))
                {
                    $companyAddressParsed[BUILDING] = trim(str_ireplace(array('строение.','стр.'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'эт. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' эт.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'этаж'))
                {
                    $companyAddressParsed[LEVEL] = trim(str_ireplace(array('этаж','эт.'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'вл. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' вл.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'влд. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' влд.'))
                {
                    $companyAddressParsed[POSSESSION] = trim(str_ireplace(array('влд.','вл.'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'корп. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' корп.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'крп. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' крп.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' корпус'))
                {
                    $companyAddressParsed[HOUSING] = trim(str_ireplace(array('корпус','крп.','корп.'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'подъезд'))
                {
                    $companyAddressParsed[PORCH] = trim(str_ireplace(array('подъезд'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'оф. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' оф.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'офис'))
                {
                    $companyAddressParsed[OFFICE] = trim(str_ireplace(array('оф.','офис'), '', $addrItem));
                }
                elseif (  mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'д. ')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), ' д.')
                       || mb_strpos(mb_strtolower($addrItem, 'UTF-8'), 'дом'))
                {
                     $addrItem = trim(str_ireplace(array('д.','дом'), '', $addrItem));

                     preg_match('/([\d]*)\/([\d]*)/i', $addrItem, $matches);
                     $place = $matches[1];
                     $hsng  = $matches[2];

                     if ($place && $hsng)
                     {
                         $companyAddressParsed[PLACE]   = $place;
                         $companyAddressParsed[HOUSING] = $hsng;
                     }
                     else
                     {
                          $companyAddressParsed[PLACE] = $addrItem;
                     }
                }
                else
                {
                    /**
                     * Начинаем разбираться с хлебными крошками...
                     */

                    /**
                     * Первый шаблон - дом вида 12/13
                     */
                    preg_match('/([\d]*)\/([\d]*)/i', $addrItem, $matches);
                    $place = $matches[1];
                    $hsng  = $matches[2];

                    if ($place && $hsng)
                    {
                        $companyAddressParsed[PLACE]   = $place;
                        $companyAddressParsed[HOUSING] = $hsng;
                    }
                    else
                    {

                        /**
                         * Второй шаблон вида:     ___[номер дома]____
                         */
                        preg_match('/^[\s]*+([\d]*)[\s]*?$/i',$addrItem, $matches);
                        $place = $matches[1];
                        if ($place)
                        {
                            $companyAddressParsed[PLACE]   = $place;
                        }
                        else
                        {
                            /**
                             * 3й вариант вида: ___[номер дома][буквенное обозначение корпуса дома]____ (прим.  12Б)
                             */
                            preg_match('/^[\s]*+([\d]{1,10})+([А-Яа-я]{1,1000})[\s]*?$/i', $addrItem, $matches);
                            $place = $matches[1];
                            $hsng  = $matches[2];

                            if ($place && $hsng)
                            {
                                $companyAddressParsed[PLACE]   = $place;
                                $companyAddressParsed[HOUSING] = $hsng;
                            }
                            else
                            {
                                /**
                                 * Ну, пацаны, я уже не знаю тогда... Оставляем как название чего-то...
                                 */
                                $companyAddressParsed[COMPANY_LOCALITY] = trim($addrItem);
                            }

                        }
                    }
                }

                break;
        }
    }
    return $companyAddressParsed;
}