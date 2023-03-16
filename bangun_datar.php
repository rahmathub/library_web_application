<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rumus bangun datar</title>
</head>
<body>
    <?php

    // Judul 
    echo 
    "<h2>
        <hr>
        5 Macam menghitung luas bangun datar
        <hr>
    </h2>
    ";

    // php di dalam html
    ?>
    <img src="https://i.ytimg.com/vi/z0UiFaXg9AU/maxresdefault.jpg" width="700px">
    <?php
    // lanjut ke php

    echo 
    "<h3>
        1. Rumus menghitung Luas Bangun Datar Persegi      
    </h3>
    
    <p>Kotak biru gambar di atas adalah persegi dengan <i>sisi = 13 cm</i>
    maka untuk menghitung luasnya adalah</p><br>
    ";

    // php di dalam html
    ?>
    <div>
        <p style="font-weight:bold;font-size:20px;background-color: yellow;display:inline;">Luas (L) = sisi(S) x sisi(S)</p>
        <br>
        <ul>
            <li>Diketahui Sisi (S) = 13 cm</li>
            <li>
                <?php 
                // diketakui ukuran persegi
                $S= 13;
                $L= $S * $S;
                // pengerjaan
                echo "Luas = $S x $S = $L cm<sub>2</sub>";
                ?>
            </li>
        </ul>
    </div>
    <hr>
    <br>
    <?php
    // lanjut ke php
    echo 
    "<h3>
        2. Rumus menghitung Luas Bangun Datar Persegi Panjang     
    </h3>
    
    <p>Kotak Kuning gambar di atas adalah persegi panjang dengan <i>panjang = 12 cm dan lebar = 8 cm</i>
    maka untuk menghitung luasnya adalah yaitu sebagai berikut.</p><br>
    ";

    // php di dalam html
    ?>
    <div>
        <p style="font-weight:bold;font-size:20px;background-color: yellow;display:inline;">Luas Persegi Panjang (L) = Panjang(P) x Lebar(L)</p>
        <br>
        <ul>
            <li>Diketahui Panjang (P) = 12 cm dan Lebar (L) = 8 cm</li>
            <li>
                <?php 
                // diketakui ukuran persegi panjang
                $P= 12;
                $Le= 8 ;
                $LPP= $P * $Le;
                // pengerjaan
                echo "Luas = $P x $Le = $LPP cm<sub>2</sub>";
                ?>
            </li>
        </ul>
    </div>
    <hr>
    <br>
    <?php
    // lanjut ke php

    echo 
    "<h3>
        3. Rumus menghitung Luas Bangun Datar Segitiga     
    </h3>
    
    <p>Segitiga berwarna ungu gambar di atas adalah Bangun datar segitiga dengan <i>alas = 12 cm dan tinggi = 8 cm</i>
    maka untuk menghitung luasnya adalah yaitu sebagai berikut.</p><br>
    ";


    // php di dalam html
    ?>
    <div>
        <p style="font-weight:bold;font-size:20px;background-color: yellow;display:inline;">Luas Bangun Datar Segitiga (L) = 1/2(ALAS x TINGGI)</p>
        <br>
        <ul>
            <li>Diketahui Alas (A) = 15 cm dan Tinggi (T) = 8 cm</li>
            <li>
                <?php 
                // diketakui ukuran Segitiga
                $A= 15;
                $T= 8 ;
                $ST= 1/2;
                $SG= $A * $T;
                $HSG = $SG*$ST;
                // pengerjaan
                echo "Luas Segitiga = $ST ($A x $T) = $HSG cm<sub>2</sub>";
                ?>
            </li>
        </ul>
    </div>
    <hr>
    <br>
    <?php
    // lanjut ke php

    echo 
    "<h3>
        4. Rumus menghitung Luas Bangun Datar Lingkaran     
    </h3>
    <p>Gambar di bawah ini adalah Bangun datar lingkaran dengan <i>jari-jari = 7 cm dan diameter = 10 cm</i>
    maka untuk menghitung luasnya adalah yaitu sebagai berikut.</p><br>
    ";
    ?>
    <img src="https://i.ytimg.com/vi/BPe5Sf-Kxyw/maxresdefault.jpg" width="700px">
    <?php

        // php di dalam html
        ?>
        <div>
            <p style="font-weight:bold;font-size:20px;background-color: yellow;display:inline;">Luas Bangun Datar Lingkaran (L) = 24/7 x (R)2</p>
            <br>
            <ul>
                <li>Diketahui Diameter (D) = 10 cm dan Jari-Jari (R) = 7 cm ,Hitung kedua lingkaran tersebut</li>
                <li>
                    <?php 
                    // diketakui ukuran lingkaran

                    // Lingkaran A
                    $A= 10;
                    $Aa= $A / 2;
                    $Aaj = $Aa * $Aa;
                    $Hasil_AB = 22/7 * $Aaj; 
                    $Hasil_A = floor($Hasil_AB);
                    
                    // lingkaran B
                    $B= 7 ;
                    $Ba= $B*$B;
                    $Hasil_B = 22/7 * $Ba; 
                    // pengerjaan
                    echo "Luas Lingkaran A = 22/7 x $Aaj =  $Hasil_AB cm dibulatkan menjadi = $Hasil_A cm<sub>2</sub> <br>
                    Luas Segitiga B = 22/7 x $Ba = $Hasil_B cm<sub>2</sub>
                    ";
                    ?>
                </li>
            </ul>
        </div>
        <hr>
        <br>
        <?php
        // lanjut ke php

    
    echo 
    "<h3>
        5. Rumus menghitung Luas Bangun Datar Belah Ketupat     
    </h3>
    <p>Pada gambar di bawah, terdapat panjang dua garis diagonal, yaitu 6 cm dan 8 cm.
    Untuk menjawab luas belah ketupat tersebut, berikut adalah caranya:</p><br>
    ";
    
    ?>
    <img src="https://blue.kumparan.com/image/upload/fl_progressive,fl_lossy,c_fill,q_auto:best,w_640/v1624516383/ovxz2kcmtzi2diltfsdm.png"
    width="500px">
    <?php

    // php di dalam html
    ?>
    <div>
        <p style="font-weight:bold;font-size:20px;background-color: yellow;display:inline;">
            Luas Belah Ketupat (L) = 1/2 Diagonal A x Diagonal B
        </p>
        <br>
        <ul>
            <li>Diketahui  Diagonal A = 6 cm dan Diagonal B = 8 cm</li>
            <li>
                <?php 
                // diketakui ukuran Belah ketupat
                $D1= 6;
                $D2= 8 ;
                $r = 1/2;
                $LuasD= $r * $D1 * $D2;
                // pengerjaan
                echo "Luas Belah ketupat =  $r x $D1 x $D2 = $LuasD cm<sub>2</sub>";
                ?>
            </li>
        </ul>
    </div>
    <hr>
    <br>
    <?php
    // lanjut ke php

    ?>

    
</body>
</html>