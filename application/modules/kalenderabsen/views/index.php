<style type="text/css">

    .text-small {
        font-size: 1rem;
        display: block;
        color: rgba(255, 255, 255, .8);
        font-weight: 600;
    }

    .flot-chart--xs {
        color: rgba(255, 255, 255, 0.6);
        font-size: 1.2rem;
        text-align: center;
        text-shadow: 0px 1px rgba(1, 1, 1, 0.1);
        font-weight: 500;
    }

    .stats__info h2 {
        font-size: 1.1rem;
        font-weight: 300;
    }

    .calendar {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .calendar .header_month {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        padding: 10px 0;
    }

    .calendar .header_day {
        text-align: center;
        vertical-align: middle;
        font-size: 24px;
        padding: 10px 0;
    }

    .calendar .day,
    .calendar .today {
        font-size: 24px;
        height: 80px; 
        text-align: center;
        vertical-align: middle;
        border: 1px solid #ddd;
        position: relative;
        transition: background-color 0.3s;
    }

    .calendar .day:hover {
        background-color: #f0f0f0;
    }

    .calendar .day .no_content_fill_day {
        color: black;
    }

    .calendar .today .no_content_fill_today {
        color: white;
    }

    .calendar .today {
        background-color: #13c8e8; 
        font-weight: bold;
    }

    .calendar .day .date {
        display: block;
        margin: 10px 0;
        font-size: 20px;
    }

    .calendar .day .events {
        font-size: 14px;
        color: #666;
    }
</style>
<section id="kalenderabsen">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
        </div>
        <div class="card-body">
                <div class="row">
                    <div class="col">
                        <center>
                            <?php echo $calendar ?>
                        </center>
                    </div>
                </div>
        </div>
    </div>
</section>