var secs;
var timerID = null;
var timerRunning = true;
var delay = 1000;

var hari = new Array(7)
hari[0] = "Minggu"
hari[1] = "Senin"
hari[2] = "Selasa"
hari[3] = "Rabu"
hari[4] = "Kamis"
hari[5] = "Jumat"
hari[6] = "Sabtu"

// This array holds the "friendly" month names
var bulan = new Array(12)
bulan[0] = "Januari"
bulan[1] = "Februari"
bulan[2] = "Maret"
bulan[3] = "April"
bulan[4] = "Mei"
bulan[5] = "Juni"
bulan[6] = "Juli"
bulan[7] = "Agustus"
bulan[8] = "September"
bulan[9] = "Oktober"
bulan[10] = "November"
bulan[11] = "Desember"

function BuildDate() 
{
  sekarang = new Date();
  
  d = sekarang.getDate();
  M = sekarang.getMonth();
  y = sekarang.getFullYear();
  h = sekarang.getHours() > 9 ? sekarang.getHours() : "0" + sekarang.getHours();
  m = sekarang.getMinutes() > 9 ? sekarang.getMinutes() : "0" + sekarang.getMinutes();
  s = sekarang.getSeconds() > 9 ? sekarang.getSeconds() : "0" + sekarang.getSeconds();

  return d + " " + bulan[M] + " " + y + " " + h + ":" +  m + ":" + s;  
}

function StartTheTimer()
{
    timerID = setTimeout("StartTheTimer()", delay);
    document.jam.jamDisplay.value = BuildDate();
}
