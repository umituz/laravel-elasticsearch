<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Elasticsearch Dersleri</title>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(function () {

            function timeConverter(UNIX_timestamp) {
                var a = new Date(UNIX_timestamp * 1000);
                var year = a.getFullYear();
                var month = ("0" + (a.getMonth())).slice(-2);
                var date = ("0" + (a.getDate())).slice(-2);
                var hour = ("0" + (a.getHours())).slice(-2);
                var min = ("0" + (a.getMinutes())).slice(-2);
                var time = date + '-' + month + '-' + year + ' ' + hour + ':' + min;
                return time;
            }

            function api(postData, route) {
                console.log(postData);

                $.post(route, postData, function (sonuc) {
                    console.log("Arama Bitti");
                    sonuc = JSON.parse(sonuc);

                    $("table tbody,.arama_suresi,.arama_sonucu").text('')

                    $(".arama_suresi").text(sonuc.arama_suresi);
                    $(".arama_sonucu").text(sonuc.elasticsearch.hits.total.value);

                    sonuc.elasticsearch.hits.hits.forEach(function (item) {
                        //console.log(item._source.soyad);

                        var isim = item._source.isim + ' ' + item._source.soyad;
                        var cinsiyet = item._source.cinsiyet;
                        var ulke = item._source.ulke;
                        var yas = item._source.yas;
                        var dogum_tarihi = item._source.dogum_tarihi;
                        var ip_address = item._source.ip_address;
                        var ekleme_tarihi = timeConverter(item._source.ekleme_tarihi);

                        $("table tbody").append('<tr><td>' + isim + '</td><td>' + cinsiyet + '</td><td>' + ulke + '</td><td>' + yas + '</td><td>' + dogum_tarihi + '</td><td>' + ip_address + '</td><td>' + ekleme_tarihi + '</td></tr>')

                    });


                });
            }

            $("#terim_sorgusu").click(function () {
                var postData = {};
                postData['islem'] = "terim_sorgusu";
                postData['adet'] = $(".adet").val();
                postData['terim'] = $(".terim").val();

                api(postData, '{{ route('members.term') }}');
            });

            $("#terimler_sorgusu").click(function () {
                var postData = {};
                postData['islem'] = "terimler_sorgusu";
                postData['adet'] = $(".adet").val();
                postData['terimler'] = $(".terimler").val();

                api(postData, '{{ route('members.terms') }}');
            });


            $("#wildcard_sorgusu").click(function () {
                var postData = {};
                postData['islem'] = "wildcard_sorgusu";
                postData['adet'] = $(".adet").val();
                postData['wildcard'] = $(".wildcard").val();

                api(postData, '{{ route('members.wildcard') }}');
            });


        });
    </script>

    <style type="text/css">
        .arama {
            width: 800px;
            margin: 0 auto;
            background: #f9f9f9;
            text-align: center;
            padding: 30px;
        }

        .arama_item {
            width: 200px;
            float: left;
        }

        .arama_adet {
            text-align: left;
            margin-bottom: 30px;
        }

        input {
            padding: 5px;
        }

        .istatislik {
            text-align: center;
            font-size: 30px;
            margin: 20px 0;
        }

        .arama_suresi {
            margin-right: 50px;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            font-size: 16px;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tbody tr:nth-child(even) {
            background-color: #dddddd;
        }

    </style>
</head>

<body>

<div class="arama">

    <div class="arama_adet">
        <b>Sonuç Miktarı : </b>
        <select class="adet">
            <option value="10">10</option>
            <option value="1">1</option>
            <option value="5">5</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="1000">1000</option>
        </select>

    </div>

    <div class="arama_item">
        <input type="text" placeholder="terim" class="terim"> <br><br>
        <button id="terim_sorgusu">Terim Sorgusu</button>
    </div>

    <div class="arama_item">
        <input type="text" placeholder="terimler" class="terimler"> <br><br>
        <button id="terimler_sorgusu">Terimler Sorgusu</button>
    </div>

    <div class="arama_item">
        <input type="text" placeholder="wildcard" class="wildcard"> <br><br>
        <button id="wildcard_sorgusu">Wildcard Sorgusu</button>
    </div>

    <div style="clear: both;"></div>
</div>

<div class="istatislik">
    <b>Arama Süresi : </b> <span class="arama_suresi"></span>
    <b>Arama Sonuç : </b> <span class="arama_sonucu"></span>

    <h2>ARAMA SONUCU</h2>

    <table>
        <thead>
        <tr>
            <th>Ad Soyad</th>
            <th>Cinsiyeti</th>
            <th>Ülke</th>
            <th>Yaş</th>
            <th>Doğum Tarihi</th>
            <th>İp Adresi</th>
            <th>Kayıt Tarihi</th>
        </tr>
        </thead>

        <tbody></tbody>
    </table>

</div>


</body>
</html>
