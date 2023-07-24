
<?php

require_once 'sdbh.php';
$dbh = new sdbh();

?>
<HTML>
    <header>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="/style.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<style>


</style>
</header>
<BODY> 
<div class="container row">
                
        <div class="col-12 col-md-6 col-lg-6 col-xl-6">
            <span class="logo"><img src='/logo-white.png'/> Прокат</span>
            
            <form method="get" required>
                <h4>Введите количество дней </h4> <input type="text" name="daysNum" />
                <p><input type="submit" value="Отправить"/></p>
            </form>
        </div>
        <?
        $daysNum = (int)$_GET['daysNum'];
        $NAME = $_POST["cars"];
        $car_tarif = "";
        $options = [];
        ?>      
        <div class=" row col-12 col-md-6 col-lg-6 col-xl-6 ">
            <form method="post">
                
                <h4>Выберите модель автомобиля </h4>
                <p>
                    <select name="cars">
                    <option value="Авто 1">Авто 1</option>
                    <option value="Авто 2">Авто 2</option>
                    <option value="Авто 3">Авто 3</option>
                    </select>
                
                </p>
                <p>
                    <input type="submit" value="Отправить"/>
                </p>
                
                <? if ($NAME === "Авто 1")
                {
                    $tarif1 = unserialize($dbh->mselect_rows('a25_products', ['NAME' => 'Авто 1'], 0, 1, 'id')[0]['TARIFF']);
                    foreach($tarif1 as $k => $s) 
                        if ($daysNum >= $k)
                            $car_tarif = $s;
                }
                if ($NAME === "Авто 2")
                {
                    $tarif2 = unserialize($dbh->mselect_rows('a25_products', ['NAME' => 'Авто 2'], 0, 1, 'id')[0]['TARIFF']);
                    foreach ($tarif2 as $k => $s)  
                        if ($daysNum >= $k)
                            $car_tarif = $s;
                }
                if (isset($_POST["tarif1"]))
                    echo $item1;
                elseif (isset($_POST["tarif2"]))
                    echo $item2;

                $i = 0;?>
                
                <h4>Дополнительные опции</h4>
                <?$services = unserialize($dbh->mselect_rows('a25_settings', ['set_key' => 'services'], 0, 1, 'id')[0]['set_value']);
                foreach ($services as $k => $s) 
                { ?>
                    <li>
                        <?=$k?> <input type="checkbox" name="services[]" value=<?=$s?>/>
                    </li>
                    <?
                    $options[$i] = $s; 
                    $i+=1;
                }?>
                <p><input type="submit" value="Подтвердить"></p>
                
                </form>
                <?$i = 0;
                $extra_price = 0;
                if (isset($_POST["services"]))
                {   
                    $service = $_POST["services"];
                    foreach ($service as $item) 
                    {
                        $extra_price+=(int)$item;
                        $i+=1;
                    }
                }?>
                
                
            </div>
    
            <div class="content_block">
                <p>
                <?
                if ( isset($_POST["cars"]) && ($_POST["cars"]) == "Авто 3" )
                {
                    $result_price = 2500*$daysNum+(int)$extra_price;
                }
                else $result_price = (int)$car_tarif*$daysNum;
                if (isset($_POST["cars"]) || (isset($_POST["daysNum"])))
                {
                    $result = intval($result_price) + intval($extra_price);
                    echo ("Ваш тариф: {$car_tarif} рублей в день, \n");
                    echo ("Количество дней: {$daysNum}\n");
                if (isset($_POST["services"]))
                {
                    echo ("Дополнительные опции стоят: {$extra_price} рублей \n");
                }
                    echo ("Итог равен: {$result} рублей");
                }
                if (!isset($_POST["cars"]) && (!isset($_POST["daysNum"])))
                {
                    echo ("Выберите, пожалуйста, тариф\n");
                }
                ?>
                </p>
            </div>
            <div>
            <a class="content_toggle" href="#">Рассчитать</a> 
            </div>    
        </div>
    <script>
        $(document).ready(function(){
            $('.content_toggle').click(function(){
                $('.content_block').slideToggle(300, function(){
                    if ($(this).is(':hidden')) {
                        $('.content_toggle').html('Рассчитать');
                    } else {
                        $('.content_toggle').html('Скрыть рассчёт');
                    }							
                });
                return false;
            });
        });
        $('submit').trigger('reset');
    </script>
</BODY>
</HTML> 