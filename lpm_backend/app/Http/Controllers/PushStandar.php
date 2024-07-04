<?php
namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\PerStandars;
use App\Models\Roles;
use App\Models\Standars;
use App\Models\TipeAudits;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Models\Audits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class PushStandar extends Controller
{
    public function index()
    {
        $standars = Pushing::all();
        return response()->json($standars);
    }
    public function pushing()
    {
        $Standar = [
            'Kompetensi Lulusan','Isi Pembelajaran','Proses Pembelajaran','Penilaian pembelajaran',
            'Dosen dan Tenaga Kependidikan','Sarana dan Prasana Pembelajaran','Pengelolaan Pembelajaran','Pembiayaan Pembelajaran',
            'Hasil Penelitian','Isi Penelitian','Proses Penelitian','Penilaian Penelitian','Penelitian','Sarana dan Prasarana Penelitian',
            'Sarana Pengelolaan Penelitian','Sarana Pembiayaan Penelitian','Hasil Pengabdian kepada Masyarakat','Isi Pengabdian kepada Masyarakat','Proses Pengabdian kepada Masyarakat',
            'Penilaian Pengabdian kepada Masyarakat','Pelaksanaan Pengabdian kepada Masyarakat','Sarana dan Prasarana Pengabdian kepada Masyarakat',
            'Pengelolaan Pengabdian kepada Masyarakat','Pembiayaan Pengabdian kepada Masyarakat','Keunggulan','Kemahasiswaan dan Alumni','Visi Misi UNAI','Kerjasama',
            'Pengelolaan Sumber Daya Manusia','Tata Pamong dan Tata Kelola','Rekognisi Pembelajaran Lampau','Penerimaan Mahasiswa Baru','Perpustakaan','Sarana dan Prasarana',
            'Keuangan dan Kesejahteraan'
        ];
        $Pernyataan=[
            [
                'Setiap program studi harus menggunakan feedback dari stakeholders dalam pengembangan kompetensi lulusan
','Lulusan harus mempunyai kemampuan berbahasa Inggris ditunjukan dengan skor TOEFL
','Lulusan harus mempunyai kompetensi khusus
','Lulusan mempunyai kemampuan wirausaha
','Program studi menetapkan lulusannya harus mempunyai kompetensi program studi
','Setiap program studi harus menetapkan kualifikasi kompetensi lulusan yang meliputi pengetahuan, keterampilan dan sikap.
  ','  Setiap program studi harus menetapkan kompetensi utama untuk lulusannya.
    ','Setiap dosen membuat RPS semua mata kuliah yang diampu setiap awal semester.
    ','Setiap dosen membuat RPS dengan format seperti template dari Fakultas.
    ','Persentase mahasiswa DO atau mengundurkan diri tiap program studi < 5%
    ','Kelulusan tepat waktu > 50%
    ','Rata-rata lama lulusan dalam 3 tahun terakhir: 4 tahun untuk S1 dan 2 tahun untuk S2
','Ada evaluasi terhadap lulusan > 50%
  ','Masa tunggu lulusan mendapatkan pekerjaan pertama < 3 bulan
   '         ],[
                'Program studi harus mengidentifkasi dan menetapkan ketepatan struktur kurikulum dalam pembentukan capaian pembelajaran yang digambarkan dalam peta kompetensi.
    ','Universitas harus menentukan tingkat kedalaman dan keluasan materi pembelajaran untuk setiap program pendidikan yang dirumuskan dengan mengacu pada deskripsi capaian pembelajaran lulusan dari KKNI.
    ','Kaprodi menetapkan beban SKS minimal dan maksimal untuk mahasiswa sesuai IPS nya
','Kaprodi menetapkan rerata lama masa studi 4 tahun (S1) dan 2 tahun (S2)
','Kaprodi mewajibkan dosen untuk menyediakan RPS yang terdiri dari kontrak pembelajaran, CPL, deskripsi, pokok bahasan, evaluasi peringkat penilaian, sumber bacaan dengan lengkap.
  ','  Karprodi melakukan rapat untuk melakukan evaluasi PBM setiap awal semester.
    ','Kaprodi menetapkan waktu peninjauan kurikulum secara menyeluruh maksimal 5 tahun.
    ','Pimpinan universitas menyusun kalender akademik maksimal 2 bulan sebelum kalender akademik berikutnya.
    ','Kaprodi melibatkan dosen, mahasiswa, stakeholders, asosiasi, pimpinan fakultas dan tim kurikulum universitas.
    ','Kaprodi melakukan efektivitas kurikulum dengan mengukur waktu pertama mendapatkan pekerjaan.
    ','Kaprodi menyusun kurikulum dengan memberikan mata kuliah berorientasi masa depan minimal 2 mata kuliah.
    ','Kaprodi menyusun kurikulum dengan perkembangan IPTEKS dan kebutuhan masyarakat.
      ',      ],[
                'Dosen pengampu mata kuliah harus menyusun isi materi pembelajaran sesuai dengan RPS, memiliki kedalaman dan keluasan yang relevan untuk mencapai capaian pembelajaran lulusan, serta ditinjau ulang secara berkala.
',
    'Ketua program studi harus merancang proses pembelajaran yang terdiri atas sifat interaktif, holistik, integratif, saintifik, kontekstual, tematik, efektif, kolaboratif dan berpusat pada mahasiswa.
    ','Ketua program studi mengevaluasi kesesuaian metode pembelajaran dengan learning outcomes.
    ','Setiap dosen pengampu praktikum harus menyusun modul atau petunjuk praktikum minimal 10 kali pertemuan secara rinci dan sistematis.
    ','Persentasi kehadiran dosen seluruh pertemuan > 90%.
','Persentase mata kulah yang dalam penentuan nilai akhirnya memberikan bobot pada tugas-tugas > 30%
  ','  Integrasi kegiatan penelitian PkM dalam pembelajaran > 30% jumlah penelitian dan PkM DTPS yang hasilnya telah diintegrasikan ke dalam mata kuliah dalam 3 tahun terakhir.
    ','Tingkat kepuasan mahasiswa kepada dosen mengajar lebih besar sama dengan 4 (skala 1 - 5).
      '      ],[
               ' Setiap program studi di UNAI mendesain mutu pelaksanaan penilaian pembelajaran meliputi proses dan hasil belajar mahasiswa untuk mengukur ketercapaian pembelajaran lulusan berdasarkan prinsip penilaian yang mencakup, edukatif, otentik, objektif, akuntabel dan transparan yang dilakukan secara terintegrasi.
    ','Dosen pengampu mata kuliah harus melaksanakan penilaian pembelajaran yang terdiri atas teknik dan instrumen penilaian. Teknik penilaian terdiri dari partisipasi, ujian tertulis, ujian lisan, presentasi kelas.
    ','Dosen mata kuliah melaksanakan pembelajaran dengan memuat unsur-unsur sebagai berikut: 1) Mempunyai kontrak rencana penilaian. 2) Melaksanakan penilaian sesuai kesepakatan. 3) Memberikan umpan balik dan memberi kesempatan untuk mempertanyakan hasil kepada mahasiswa. 4) Mempunyai dokumentasi penilaian proses dan hasil belajar mahasiswa. 5) Mempunyai prosedur yang mencakup tahap perencanaan, kegiatan pemberian tugas atau soal, observasi kinerja, pengembalian hasil observasi dan pemberian nilai akhir. 6) Pelaporan penilaian berupa kualifikasi keberhasilan mahasiswa dalam menempuh suatu mata kuliah dalam bentuk huruf dan angka. 7) Mempunyai bukti-bukti rencana dan telah melakukan proses perbaikan berdasarkan hasil monev penilaian
','Soal ujian atau instrumen penilaian untuk semua mata kuliah tersedia 100%.
','Persentasi bobot penilaian setiap dosen pengampu mata kuliah dengan bobot nilai 20% kehadiran, 15 tugas mandiri, 15% persentase, 25% UTS dan 25% UAS.
  ','  Penyampaian yudisium
','Rata-rata IPK lulusan. Untuk D3 dan S1 lebih besar sama dengan 2,33. Untuk S2 lebih besar sama dengan 3.
','Penilaian lamanya lulus masa studi. Untuk D3: 2,5 tahun ≤ masa studi ≤ 4 tahun. Untuk S1: 3,5 tahun ≤ masa studi ≤ 5 tahun. Untuk S2: 1,5 tahun ≤ masa studi ≤ 3 tahun.
  ','  Penilaian persetanse kelulusan tepat waktu ≥ 50%
    '.'Penilaian persentase keberhasilan studi ≥ 85%.
      '      ],[
                'Dosen harus memiliki kualifikasi akademik dan kompetensi pendidik, sehat jasmani dan rohani, serta memiliki kemampuan untuk menyelenggarakan pendidikan dalam rangka pemenuhan capaian pembelajaran lulusan.
   ',' Dosen harus memiliki kualifikasi akademik pada tingkat pendidikan paling randah dalam rangka pemenuhan capaian pembelajaran lulusan yang dibuktikan dengan ijazah. Minimal S2 untuk dosen S1 dan S3 untuk dosen S2.
    ','Dosen dalam rangka pemenuhan capaian pembelajaran lulusan harus memiliki kompetensi pendidik yang dinyatakan dengan serfifikat pendidik dan sertifikat profesi.
    ','Dosen program sarjana harus berkualifikasi akademik paling rendah lulusan magister yang relevan dengan program studi, dan dosen bersertifikat yang relevan dengan program studi dan berkualifikasi paling rendah setara dengan jenjang 8 KKNI.
    ','Dosen program magister harus berkualifikasi akademik lulusan doktor yang relevan dengan program studi dan dosen bersertifikasi profesi yang relevan dengan program studi dan berkualifikasi setara dengan 9 KKNI.
    ','Dosen memiliki beban kerja yang harus disesuaikan dengan besarnya beban tugas tambahan, bagi dosen yang mendapatkan tugas tambahan pada setiap masa evaluasi.
    ','Dosen memiliki beban kerja sebagai pembimbing utama dalam penelitian terstruktur dalam rangka penyusunan skripsi/tugsa akhir, tesis.
    ','Dalam pemenuhan beban kerjanya, dosen mengacu pada nisbah dosen dan mahasiswa yang diatur dalam peraturan menteri.
    ','Ketersediaan dosen tetap yang ditugaskan menjalankan proses pembelajaran pada setiap prodi secara penuh waktu.
    ','Tenaga kependidikan kecuali tenaga administrasi harus memiliki kualifikasi akademik paling rendah lulusan program diploma 3 yang dinyatakan dengan ijazah sesuai dengan kualifikasi tugas pokok dan fungsinya.
    ','Tenaga administrasi yang melayani kegiatan pendidikan harus memiliki kualifikasi akademik minimum yang dibuktikan dengan ijazah.
    ','Tenaga kependidikan yang melayani kegiatan pendidikan yang memerlukan keahlian khusu harus memiliki sertifikat kompetensi sesuai dengan bidang tugas dan keahliannya.
      '      ],[
                'Dalam melaksanakan proses pembelajaran setiap program studi harus memenuhi standar sarana akademik yang diperlukan sebagai kelengkapan yang disediakan untuk menjalankan fungsinya, meliputi: sarana pembelajaran yang terdiri dari papan tulis, penghapus spidol, whiteboard, LCD, alat peraga, bahan habis pakai, computer dan printer, peralatan laboratorium sesuai dengan prodi masing-masing. Sarana sumber belajar: buku teks, jurnal ilmiah, fasilitas internet, fasilitas e-learning, fasilitas turnitin dan sumber belajar lainnya.
    ','Dalam melaksanakan proses pembelajaran, setiap program studi harus memenuhi standar prasarana akademik yang diperlukan sebagai kelengkapan yang disediakan untuk menjalankan fungsinya, yaitu: 1) Prasarana bangunan yang mencakup lahan dan bangunan Gedung yang diperlukan, meliputi: ruang kuliah, ruang kantor, ruang dosen, ruang semiinar, ruang rapat, ruang laboratorium, ruang perpustakaan, ruang komputer, kebun percobaan, ruang umum, pusat pelayanan mahasiswa, pra sararan olahraga dan lain-lain. 2) Prasarana umum berupa air, toilet, sanitasi, listrik, jaringan internet, lapangan parkir dosen dan mahasiswa.
      '      ],
            [
               ' Rektor harus menyusun kebijakan rencana strategis dan operasional terkait dengan pembelajaran yang dapat diakses oleh sivitas akademika dan pemangku kepentingan, serta dapat dijadikan pedoman bagi program studi dalam melaksanakan program pembelajaran.
    ','Dekan memastikan bahwa kaprodi melakukan penyususnan dan pengembangan kurikulum.
    ','Dekan dan kaprodi memastikan bahwa dosen menyusun rencana pembelajaran pada setiap mata kuliah
','Wakil Rektor 1 dan Dekan menyusun kebijakan formal dan pedoman yang komprehensif dan rinci untuk mengintegrasikan kegiatan penelitian dan PkM ke dalam pembelajaran.
  ','  Dekan dan kaprodi menetapkan kalender akademik dengan mengacu pada kalender akademik UNAI.
    ','Dekan harus melakukan pemantauan evaluasi terhadap kegiatan program studi dalam melaksanakan kegiatan pembelajaran minimal 1 semester sekali.
    ','Rektor harus memastikan data yang dilaporkan melalui PDDIKTI merupakan data yang akurat, valid dan mutakhir dan dapat digunakan untuk mengukur ketercapaian atua pemenuhan standar DIKTI.
    ','Fakultas menyediakan ketersediaan bukti panduan tugas akhir yang komprehensif.
    ','Kaprodi memastikan setiap mahasiswa mendapat pembimbing akademik 1 minggu sebelum menginput KRS.
    ','Kaprodi memeriksa bahwa dosen minimal 4 kali/semester melakukan pembimbingan akademik.
      '      ],[
                'Program studi menyusun standar pembiayaan pembelajaran yang merupakan kriteria minimal tentang komponen dan besaran biaya investasi dan biaya operasional yang disusun dalam rangka pemenuhan capaian pembelajaran lulusan.
    ','Wakil Rektor 1, Wakil Rektor II, Dekan, BAA, dan BAK menetapkan kebiakan penyususnan program kerja tahunan dan kebijakan anggaran pembiayaan pembelajaran.
    ','Fakultas, Prodi, dan Kepala BAA menyusun anggaran pembiayaan pembelajaran.
    ','UPT Komputer menyediakan sistem informasi yang memungkinkan akses dokumen panduan dokkumen panduan pembiayaan pembelajaran.
    ','Fakultas, Prodi, BAA, Kemahasiswaaan, dan Keuangan serta UPT Komputer dengan kewenangan masing-masing harus memastikan kesesuaian standar pencairan pembiayaan pembelajaran dengan pedoman keuangan.
    ','Dekan, Kaprodi, Kepala BAA dengan kewenangan masing-masing harus memanfaatkan pembiayaan pembelajaran sesuai dengan anggaran dan pedoman keuangan UNAI.
    ','Fakultas, Kepala BAA, dan BAK UNAI melakukan pengendalian, pembiayaan, dan pembelajaran.
    ','UPT Komputer dan Bendahara memfasilitasi aplikasi monitoring anggaran dan realisasi pembiayaan pembelajaran.
      '      ],[
               ' Universitas Advent Indonesia harus menjaga mutu hasil penelitian dosen yang dapat dipertanggungjawabkan secara akademis, moral, dan etika melalui desiminasi hasil penelitian.
    ','UNAI harus mengarahkan hasil penelitian dosen agar bertujuan untuk mengembangkan ilmu pengetahuan dan teknologi, serta meningkatkan kesejahteraan masyarakat dan daya saing bangsa.
    ','Universitas Advent Indonesia mewajibkan Setiap penelitian menghasilkan luaran dalam bentuk: a) publikasi ilmiah bertaraf nasional dan internasional, b) teknologi tepat guna, rekayasa sosial, model, atau kebijakan, c) produk yang dapat dimanfaatkan pemangku kepentingan, d) buku ajar atau bahan ajar, atau e) hak kekayaan intelektual. f) buku referensi.
    ','UNAI harus memfasilitasi penelitian yang dilakukan dosen untuk minimal ada 75% hasil penelitian dosen yang terpublikasikan dalam prosiding, terbitan berkala ilmiah yang memenuhi persyaratan untuk diakreditasi, terbitan berkala ilmiah terakreditasi, atau terbitan berkala ilmiah bertaraf international.
    ','Hasil penelitian mahasiswa, harus mengarah pada terpenuhinya capaian pembelajaran lulusan serta memenuhi ketentuan dan peraturan di Perguruan Tinggi.
    ','LPPM harus menyediakan penerbitan (jurnal) ilmiah untuk mempublikasi hasil penelitian dosen.
',
'Universitas Advent Indonesia dan LPPM harus mendorong dan memfasilitasi para peneliti untuk memperoleh HAKI yang meliputi hak cipta, hak paten, merek, dan yang sejenisnya dari hasil penelitian.
','    UNAI mewajibkan setiap hasil penelitian dosen didesiminasikan minimal di tingkat prodi.
',
'LPPM setiap tahun harus mengadakan desiminasi hasil penelitian tingkat nasional.
',
'UNAI harus mendorong peneliti untuk memanfaatkan hasil penelitian guna perbaikan proses pembelajaran di UNAI.
',    'LPPM harus melakukan review terhadap pelaporan hasil penelitian untuk menjamin terpenuhinya aspek komprehensif, rinci, relevan, mutakhir, dan disempaikan tepat waktu.
'
            ],[
                'UNAI harus memiliki Rencana Strategis Penelitian yang memuat landasan pengembangan, peta jalan penelitian, sumber daya (termasuk alokasi dana penelitian internal), sasaran program strategis dan indikator kinerja, serta berorientasi pada daya saing internasional.
',
'UNAI harus memastikan pedoman penelitian disosialiasikan dengan baik, mudah diakses, sesuai dengan rencana strategis penelitian, serta dipahami oleh stakeholders.
','    Universitas/Fakultas/Jurusan harus membentuk kelompok riset yang fungsional dengan bukti formal keberadaannya.
  ',  'Kelompok riset harus terlibat aktif dalam jejaring tingkat nasional dan internasional.
',
'Kelompok riset harus menghasilkan produk riset yang bermanfaat bagi masyarakat dan berdaya saing internasional.
'.
'Pengembangan laboratorium riset seharusnya sesuai dengan prioritas bidang kajian dari setiap fakultas/prodi.
','    Universitas/Fakultas setiap tahunnya harus memprogramkan penelitian dasar dan terapan dana internal UNAI.
  ','  Penelitian dasar dan terapan harus memiliki kedalaman dan keluasan materi.
    ','Universitas/Fakultas harus memastikan penelitian dasar berorientasi pada luaran penelitian berupa penjelasan atau penemuan untuk mengantisipasi suatu gejala, fenomena, kaidah, model, atau postulat baru.
    ','Universitas/Fakultas harus memastikan penelitian terapan berorientasi pada luaran penelitian yang berupa inovasi serta pengembangan ilmu pengetahuan dan teknologi yang bermanfaat bagi masyarakat, dunia usaha, dan/atau industri.
    ','Universitas/Fakultas harus memastikan penelitian dasar dan terapan memuat prinsip-prinsip kemanfaatan, kemutahiran, dan mengantisipasi kebutuhan masa mendatang.
      '      ],[
                'Universitas/LPPM harus menjamin proses penelitian mengikuti tahapan pengajuan evaluasi, persetujuan proposal, pelaksanaan penelitian, diseminasi hasil pelaporan, dan publikasi.
    ','Peneliti harus mempertimbangkan standar mutu, keselamatan kerja, kesehatan, kenyamanan, serta keamanan peneliti, masyarakat, dan lingkungan.
    ','Universitas/LPPM/LPM harus melakukan evaluasi terhadap perencanaan penelitian tatacara penilaian dan review, legalitas pengangkatan reviewer, dan hasil penilaian usul penelitian.
    ','Universitas/Fakultas harus menyediakan kebutuhan yang diperlukan untuk kegiatan penelitian yang dilakukan oleh mahasiswa dapat berupa Program Kreativitas Mahasiswa (PKM) dan tugas akhir (skripsi, tesis, atau disertasi) yang memenuhi ketentuan dan peraturan Universitas.
    ','Universitas harus menentukan besaran satuan kredit semester kegiatan penelitian yang dilakukan oleh mahasiswa.
    ','LPPM harus memeriksa Pelaporan penelitian harus memiliki dokumentasi output penelitian.
',
'LPPM harus melakukan pengendalian untuk memastikan proses penelitian berjalan sesuai dengan kriteria yang ditetapkan melalui sistem informasi manajemen LPPM yang terintegrasi.
',
'Universitas/LPPM/Fakultas harus mereview keterlaksanaan proses penelitian dan menindaklanjuti hasil temuan untuk peningkatan.
'            ],[
                'Universitas harus menetapkan POB tata cara mereview proposal penelitian dan laporan penelitian yang dijadikan pegangan oleh reviewer untuk melakukan review terhadap usl dan laporan penelitian.
    ','Universitas harus menerbitkan surat pengangkatan reviewer sebagai bukti legal formal bagi reviewer untuk melaksanakan tugasnya.
    ','LPPM harus mendokumentasikan hasil penilaian usulan penelitian sebagai bentuk akuntabilitas dan transparansi penilaian proposal.
    ','Universitas harus menerbitkan surat tugas penelitian dan kerjasama penelitian sebagai bentuk legalitas keterlaksanaan suatu penelitian
','Dalam melakukan monitoring dan evaluasi pelaksanaan penelitian, LPPM harus menyediakan berita acara kegiatan monitoring dan evaluasi pelaksanaan penelitian.
  ','  Peneliti harus menyerahkan output penelitian baik hardcopy maupun softcopy sebagai bukti pelaksanaan penelitian dan sebagai dokumen hasil penelitian bagi universitas
','LPPM harus melakukan review pada awal, selama proses, dan akhir penelitian serta mendokumentasikan bukti kegiatan review secara berkala.
  ','  Peneliti wajib menindak lanjuti hasil review dibuktikan dengan dokumen kemajuan penelitian sebagai tindak lanjut hasil review.
    ','Universitas harus menyediakan instrumen pemantauan proses dan hasil penelitian sebagai alat untuk memperoleh data tentang pelaksanaan penelitian.
    ','Universitas harus membuat kontrak penelitian dengan peneliti untuk menjamin ketepatan waktu penyusunan laporan penelitian.
    ','Pengelola kegiatan penelitian harus mendokumentasikan kegiatan penelitian dalam bentuk laporan kegiatan penelitian yang bersifat komprehensif, renci, relevan, mutakhir, dan tepat waktu.
    ','Universitas harus memiliki Standar penilaian penelitian sebagai panduan bagi reviewer untuk melakukan penilaian proses dan hasil penelitian yang dilakukan oleh peneliti.
    ','Universitas harus menyediakan panduan penyelenggaraan monitoring dan evaluasi yang memuat metode dan instrumen untuk mengukur kinerja proses dan hasil penelitian.
    ','LPPM wajib memiliki bukti pelaporan hasil kegiatan penelitian dan monitoring kepada Rektor dan mitra/penyandang dana.
      '      ],
            [
                'Peneliti harus memiliki kompetensi dalam menyusun proposal penelitian, melaksanakan penelitian, melaporkan hasil penelitian, menyusun artikel hasil penelitian, dan menyusun laporan keuangan penelitian.
    ','Peneliti harus memiliki kemampuan tingkat penguasaan metodologi penelitian yang sesuai dengan bidang keilmuan, objek penelitian, serta tingkat kerumitan dan tingkat kedalaman penelitian.
    ','Peneliti yang berkedudukan sebagai ketua dan anggota pelaksana kegiatan penelitian memiliki kualifikasi pendidikan dan jabatan sesuai ketentuan yang berlaku di setiap skim penelitian.
    ','Anggota pelaaksana seharusnya merupakan kombinasi dosen dan mahasiswa.
    ','Kolaborasi penelitian antara dosen dan mahasiswa.
    ','Setiap ketua dan anggota pelaksana kegiatan penelitian harus memiliki kompetensi sesuai dengan bidang ilmu yang dikembangkannya.
    ','Setiap ketua dan anggota pelaksana kegiatan penelitian, khususnya dosen harus mempunyai peta kegiatan penelitian atau rekam jejak atau roadmap yang jelas dan relevan dengan bidang ilmu yang dikembangkannya.
     '       ],['Universitas/Fakultas/PPs/Lembaga/Jurusan harus menyediakan sarana dan prasarana yang relevan dan mutakhir seperti laboratorium, bengkel, studio, dan yang lainnya untuk menunjang kegiatan penelitian terkait dengan bidang ilmu program studi, proses pembelajaran, dan kegiatan penelitian yang sesuai dengan kebutuhan dosen dan mahasiswa, termasuk yang berkebutuhan khusus.
    ','Universitas/Fakultas/PPs/Lembaga/Jurusan harus memiliki sistem informasi untuk layanan penelitian yang terbukti efektif memenuhi aspekaspek berikut: 1) ketersediaan layanan ICT untuk e-learning, perpustakaan (e-journal, e-book, erepository, dll.), 2) udah diakses oleh sivitas akademika, dan 3) seluruh jenis layanan dievaluasi secara berkala yang hasilnya ditindak lanjuti untuk penyempurnaan sistem informasi
','Universitas/Fakultas/PPs/Lembaga/Program Studi harus memiliki sistem informasi untuk layanan administrasi penelitian yang terbukti efektif memenuhi aspek-aspek berikut: 1) mencakup layanan akademik, keuangan, SDM, dan sarana dan prasarana aset), 2) mudah diakses oleh seluruh unit kerja dalam lingkup institusi, 3) lengkap dan mutakhir, 4) seluruh jenis layanan telah terintegrasi dan digunakan untuk pengambilan keputusan, dan 5) seluruh jenis layanan yang terintegrasi dievaluasi secara berkala dan hasilnya ditindak lanjuti untuk penyempurnaan sistem informasi.
  ','  Universitas/Fakultas/PPs/Lembaga/Jurusan harus melakukan evaluasi kecukupan sarana dan prasarana untuk penelitian memenuhi standar mutu, keselamatan kerja, kesehatan, kenyamanan, dan keamanan.
    ','Sarana dan prasarana penelitian seperti laboratorium, benkel, lapangan, studio, dan yang lainnya yang dimiliki Universitas/Fakultas/PPs/Lembaga/Prodi seharusnya telah tersertifikasi.
    ','Setiap sarana dan prasarana yang digunakan untuk penelitian harus disertai dengan spesifikasi dan manual untuk penggunaan, pemeriksanaan, dan perawatan.
    ','Universitas/Fakultas/PPs/Lembaga/ Program Studi secara periodik harus merawat dan memelihara sarana dan prasarana untuk kebutuhan penelitian.
    ','Universitas/Fakultas/PPs/Lembaga/ Ketua program studi secara periodik harus melakukan peningkatan sarana dan prasarana guna mendorong dan memfasilitasi dosen untuk melakukan kerja sama dalam melaksanakan kegiatan penelitian dengan universitas atau lembaga lain, baik di lingkup nasional maupun internasional.
', 'Sarana dan prasarana memenuhi standar mutu, keselamatan kerja, kesehatan, kenyamanan, dan keamanan.'],[
              '  Universitas Advent Indonesia (UNAI) membentuk lembaga penelitian dan pengabdian secara terintegrasi untuk mengelola penelitian dan pengabdian kepada masyarakat untuk menunjang visi UNAI menjadi universitas berkelas dunia.
    ','LPPM UNAI memiliki kewenangan mengelola pendanaan kegiatan penelitian di UNAI dari berbagai sumber (pengelolaan satu pintu) sehingga kegiatan penelitian dapat selaras dengan Renstra Penelitian UNAI.
    ','UNAI/ LPPM menyusun dan menetapkan Rencana Induk Penelitian (Renstra Penelitian) yang memuat landasan pengembangan, peta jalan penelitian, sumber daya, sasaran program strategis dan indikator kinerja sebagai pedoman dan acuan peneliti civitas akademika UNAI sesuai dengan rencana strategis penelitian UNAI.
    ','LPPM UNAI menyusun Buku Panduan Penelitian di lingkungan UNAI dengan mengacu pada Panduan Pelaksanaan Penelitian Direktorat Penelitian dan Pengabdian Kepada Masyarakat, Kementerian Riset, Teknologi, dan Pendidikan Tinggi.
    ','LPPM UNAI menerbitkan agenda kegiatan penelitian dalam jangka pendek, jangka menengah, dan jangka
','LPPM UNAI menetapkan standar pengelolaan minimal penelitian meliputi perencanaan, pelaksanaan, pengendalian, pemantauan dan evaluasi, serta pelaporan kegiatan penelitian.
  ','  LPPM UNAI menyusun buku panduan penelitian yang memuat tentang cara atau prosedur pengajuan proposal, pelaksanaan, dan pelaporan berbagai jenis kegiatan penelitian dan disosialisasikan ke semua dosen.
    ','LPPM UNAI memberikan penawaran kegiatan penelitian kepada dosen dilengkapi jadwal kegiatan yang rinci dan realistik, meliputi jadwal pengajuan proposal, penilaian proposal, pengumuman penenrimaan, seminar awal, pelaksanaan kegiatan, monitoring, seminar akhir, dan batas akhir pengumpulan laporan akhir.
    ','LPPM UNAI menetapkan reviewer, pembahas, dan penilai pada kegiatan penilaian proposal, semianr awal dan hasil kegiatan penelitian, yang memiliki kompetensi akademik sesuai dengan bidang kegiatan penelitian.
    ','LPPM menyusun instrumen yang kredibel dalam proses penlilaian proposal, seminar proposal, dan seminar hasil penelitian.
    ','LPPM UNAI melakukan pemantauan terhadap perencanaan, pelaksanaan, pengendalian, pemantauan dan evaluasi kegiatan penelitian.
    ','LPPM memiliki kelompok penleiti (research group) yang ditentukan oleh program studi sesuai bidang keahlian sehingga mampu menghasilkan penelitian yang unggul.
    ','LPPM UNAI melakukan kontrak penelitian dengan dosen yang ditandatangani dan mengikat kedua belah pihak, berisi hak dan kewajiban LPPM dan tim kegiatan penelitian.
    ','LPPM UNAI menyelenggarakan kegiatan berkala untuk meningkatakan kualitas penelitian dosen UNAI seperti pelatihan/workshop dalam hal kualitas proposal, kualitas pelaksanaan proses kegiatan penelitian dan kualitas penulisan artikel hasil kegiatan penelitian dosen dan/atau mahasiswa UNAI.
    ','LPPM UNAI mewajibkan setiap dosen di UNAI untuk melakukan kegiatna penelitian minimal sekali dalam setahun, baik mandiri, tim, atau melibatkan mahasiswa dengan mematahi kaidah/norma dan etika akademik sesuai dengan prinsip otonomi keilmuan.
    ','LPPM UNAI mewajibkan setiap peneliti untuk menjalin kerjasama dengan mitra yang ditandai dengan surat pernyataan sebagai mitra salah satu unsur penting dalam kinerja penelitian.
    ','LPPM UNAI menindak lanjuti hasil-hasil penelitian untuk pengadian masyarakat, pengajuan hak paten dan HAKI serta menjalin dengan berbagai pihak dapat meningkatkan nilai guna hasil riset.
    ','UNAI memberikan penghargaan terhadap peneliti sesuai dengan prosedur yang berlaku.
      '      ],[
                'Sumber pendanaan penelitian berasal dari luar negeri, luar PT dalam negeri, dan PT sendiri/mandiri.
    ','Universitas harus menetapkan dana penelitian untuk dosen setiap tahun.
    ','Universitas harus membiayai kegiatan penelitian dosen yang proposalnya dianggap layak.
    ','Universitas harus mengevaluasi serapan dana penelitian setiah akhir tahun.
    ','Universitas harus memonitor serapan dana penelitian dosen sebelum waktu penelitian selesai.
    ','Universitas harus berusaha meningkatkan besarnya dana penelitian untuk dosen setiap tahun.
     '       ],[
                'Dosen harus menghasilkan 1 kegiatan pengabdian kepada masyarakat dalam 1 tahun sebanyak minimal 1 kegiatan sebagai salah satu bentuk tri dharma perguruan tinggi dengan kegiatan sivitas akademika yang memanfaatkan pengetahuan dan teknologi dan memajukan kesejahteraan masyarakat.
    ','Wakil Rektor 1 dibantu oleh Ketua LPPM menetapkan standar minimal hasil PkM dalam menerapkan, mengamalkan, dan membudayakan ilmu pengetahuan dan teknologi guna memajukan kesejahteraan umum dan mencerdaskan kehidupan bangsa.
    ','Rektor mengesahkan standar minimal hasil pengabdian kepada masyarakat dalam menerapkan, mengamalkan, dan membudayakan ilmu pengetahuan dan teknologi guna memajukan kesejahteraan umum dan mencerdaskan kehidupan bangsa.
    ','Ketua pengabdi bersama tim dapat mempertanggungjawabkan hasil kegiatan pengabdian kepada masyarakat baik secara akademis, moral dan etika, serta dapat: a) dimanfaatkan untuk pengayaan pembelajaran dan penelitian; b) dimanfaatkan untuk meningkatkan taraf hidup kesejahteraan masyarakat, dan pemberdayaan masyarakat; c) dimanfaatkan sebagai model pemecahan masalah, rekayasa sosial; atau d) diterapkan langsung oleh masyarakat, dunia usaha, industri, dan/atau pemerintah.
    ','Dekan dibantu Wakil Dekan 1 berkoordinasi dengan Kaprodi wajib menyelenggarakana desiminasi setiap hasil PkM paling rendah di tingkat prodi.
    ','Tim PkM harus menghasilkan kegiatan PkM dalam bentuk: a) artikel populer atau publikasi ilmiah; b) teknologi tepat guna, rekayasa sosial, model, atau kebijakan; c)) produk atau program atau modul yang bermanfaat bagi pemangku kepentinganl d) buku ajar atau bahan ajar; atau e) hak kekayaan intelektual.
      '      ],[
 '               Dosen program studi wajib memiliki rencana induk PkM berupa peta jalan, sasaran program strategi, indikator kinerja, dan daya saing yang memiliki kedalaman dan keluasan materi PkM sesuai dengan bidang keahlian yang disusun setiap tiga tahun sekali sebagai upaya turut serta memajukan kesejahteraan umum dan mencerdaskan kehidupan bangsa.
 ','   Dosen program studi melakukan PkM setiap semester sekali untuk merealisasikan peta jalan yang telah ditetapkan setiap tiga tahun sekali dengan melibatkan beberapa mahasiswa yang berorientasi pada penyelesaian masalah dalam masyarakat
','Dosen program studi melibatkan beberapa mahasiswa melakukan PkM dengan bersumber materi dari hasil penelitian atau pengembangan ilmu pengetahuan dan teknologi tepat guna yang sesuai dengan kebutuhan masyarakat
','Universitas/Fakultas melakukan evaluasi kesesuaian PkM yang dilakukan oleh dosen program studi dan mahasiswa dengan peta jalan yang telah ditetapkan dosen program studi setiap tiga tahun sekali untuk bahan pengembagan ilmu pengetahuan dan teknologi yang relevan dengan bidang keahlian dan peta jalan PkM.
  ','Dosen prodi dibantu mahasiswa membuat bahan ajar atau modul pelatihan sebagai salah satu bahan pengayaan dan sumber belajar untuk khalayak sasaran setiap melaksanakan PkM.
',
'Universitas/Fakultas/Dosen prodi menggunakan hasil evaluasi PkM untuk perbaikan relevansi PkM dengan peta jalan dan pengembangan keilmuan program studi yang dilaukan setiap semester.
',
'Dosen program studi dibantu mahasiswa menambah muatan pendidikan karakter, penaman mental kebangsaan, bela negara, dan anti radikalisme setiap melaksanakan kegiatan PkM kepada khalayak sasaran.
','    Dosen program studi dibantu mahasiswa menyesuaikan urgensi materi atau permasalahan dengan rencana program prioritas unggulan wilayah mitra atau khalayak sasaran setiap melaksanakan PkM.
  ','Dosen prodi dibantu mahasiswa menerapkan penggunaan teknologi informasi dan komunikasi sebagai upaya efisiensi pelaksanaan kegiatan PkM.
'
            ],
            [
                'Standar proses pengabdian kepada masyarakat merupakan kriteria minimal tentang kegiatan pengabdian kepada masyarakat, yang harus terdiri atas perencanaan, pelaksanaan, dan pelaporan kegiatan.
    ','UNAI memiliki kelompok Tim PkM dosen
',
'Tim pengabdi harus merancang kegiatan pengabdian kepada masyarakat berupa: a. Pelayanan kepada masyarakat dan mahasiswa dalam negeri dan mahasiswa asing; b. Penerapan ilmu pengetahuan dan teknologi sesuai dengan bidang keahliannya; c. Pembinaan mahasiswa dalam bidang penalaran, keterampilan, dan seni; d. Peningkatan kapasitas masyarakat; atau e. Pemberdayaan masyarakat berbasis pendidikan pengembangan berkelanjutan.
','UNAI harus membiayai proses pengabdian kepada masyarakat
',
'LPPM harus membentuk tim seleksi proposal PkM
','Hasil evaluasi proposal PkM harus disampaikan kepada pengusul dan ditayankan melalui web LPPM
',
'Tim Pengabdi harus melaporkan hasil kegiatan PkM
',
'Kegiatan pengabdian kepada masyarakat wajib harus mempertimbangkan standar mutu, menjamin keselamatan kerja, kesehatan, kenyamanan, keamanan pelaksana, masyarakat, dan lingkungan.
  '          ],[
                'LPPM harus membentuk tim untuk melakukan penilaian proses dan hasil pengabdian kepada masyarakat.
   ',' LPPM harus menetapkan kriteria minimal penilaian hasil PkM yang meliputi 5 aspek 1) tingkat kepuasan masyarakat; 2) terjadinya perubahan sikap, pengetahuan, dan keterampilan pada masyarakat sesuai dengan sasaran program; 3) dapat dimanfaatkannya ilmu pengetahuan dan teknologi di masyarakat secara berkelanjutan; 4) terciptanya pengayaan sumber belajar dan/atau pembelajaran serta pematangan sivitas akademika sebagai hasil pengembangan ilmu pengetahuan dan teknologi; atau 5) teratasinya masalah sosial dan rekomendasi kebijakan yang dapat dimanfaatkan oleh pemangku kepentingan.
    ','LPPM melakukan penilaian PkM dengan menggunakan metode dan instrumen yang relevan, akuntabel, dan dapat mewakili ukuran ketercapaian kinerja proses dan pencapaian kinerja hasil
','LPPM harus menyelenggarakan penilaian proses dan hasil PkM yang dilakukan secara terintegrasi dengan prinsip penilaian edukatif, objektif, akuntabel, transparan, dan memperhatikan kesesuaian antara tujuan dan capaian kegiatan.
  ','  LPPM harus memantau pelaksanaan penilaian proses dan hasil PkM dilaksanakan sesuai dengan ketentuan.
    ','LPPM harus menentukan tindak lanjut sesuai hasil pemantauan.
    ','LPPM harus menyediakan sistem informasi berbasis teknologi terkini untuk penilaian PkM dan evaluasinya.
      '      ],[
                'Perguruan tinggi melalui LPPM harus menyusun Rencana Strategis PKM yang memuat landasan pengembangan, peta jalan PkM, sumber daya (termasuk alokasi dana PkM internal), sasaran program strategis dan indikator kinerja, serta berorientasi pada daya saing internasional.
',
  '  Perguruan tinggi melalui LPPM harus menyusun pedoman PKM yang disusun sesuai dengan renstra PkM yang mudah dipahami oleh pemangku kepentingan.
  ','  Perguruan Tinggi melalui LPPM harus mensosialisasikan pedoman PKM melalui media yang mudah diakses.
    ','Perguruan tinggi melalui LPPM harus mengevaluasi pedoman PkM secara periodik.
    ','Perguruan tinggi melalui LPPM seharusnya memastikan dan menjamin bahwa pedoman PkM yang disusun telah sesuai dengan renstra, mudah dipahami, dan disosialisasikan.
',
'Perguruan tinggi melalui LPPM melakukan revisi dokumen pedoman PkM setelah dilakukan evaluasi.
','    Perguruan tinggi melalui LPPM memiliki bukti sahih tentang pelaksanaan proses PkM yang meliputi: 1) Tatacara penilaian dan review; 2) Legalitas pengangkatan reviewer; 3) Hasil penilaian usul PkM; 4) Legalitas penugasan pelaksana PkM/Kerjasama PkM; 5) Berita acara hasil monitoring dan evaluasi; 6) Dokumentasi output PkM.
  ','  LPPM melaporkan kegiatna PkM kepada rektor (pimpinan perguruan tinggi) dan mitra/pemberi dana secara komprehensif, rinci relevan, mutakhir, dan disampaikan tepat waktu.
    ','LPPM harus memiliki dokumen hasil laporan kegiatan PkM kepada rektor (pimpinan perguruan tinggi) dan mitra/pemberi dana secara komprehensif, rinci, relevan, mutakhir, dan disampaikan tepat waktu.
    ','Perguruan tinggi harus memiliki bukti legal formal keberadaan kelompok pelaksana PkM.
    ','Perguruan tinggi harus menjamin dihasilkan produk pKm yang bermanfaat untuk menyelesaikan permasalahan di masyarakat.
    ','Perguruan tinggi harus menjamin dihasilkan produk PkM yang berdaya saing nasional.
    ','Perguruan tinggi melalui LPPM harus menyusu tim pelaksana pengabdian kepada masyarakat yang terdiri dari dosen dan mahasiswa, sedangkan anggota pelaksana merupakan kombinasi dosen dan mahasiswa.
    ','Perguruan tinggi melalui LPPM harus memastikan bahwa ketua dan anggota pelaksana kegiatan PkM memiliki kualifikasi pendidikan dan jabatan sesuai ketentuan yang berlaku di setiap skim PkM.
    ','Perguruan tinggi melalui LPPM harus memastikan jumlah anggota pelaksana PkM sesuai ketentuan yang berlaku disetiap skim PkM.
    ','Perguruan tinggi melalui LPPM harus memastikan ketua dan anggota pelaksana kegiatan PkM khusunya dosen mempunyai kompetensi dan peta kegiatan PkM atau rekam jejak yang jelas dan relevan dengan bidang ilmu yang dikembangkannya.
    ','Perguruan tinggi melalui LPPM harus memastikan bahwa kegiatan PkM yang dilaksanakan oleh dosen dan/atau mahasiswa telah mematuhi norma dan etika akademik sesuai dengan prinsip otonomi keilmuan.
    ','Perguruan tinggi melalui LPPM harus memastikan kegiatan PkM yang dilakukan oleh mahasiswa sebagai salah satu dari bentuk pembelajaran harus mengarah pada terpenuhinya capaian pembelajaran lulusan serta memenuhi ketentuan dan peraturan di perguruan tinggi.
      '      ],['Universitas/Fakultas/PPs/Lembaga/Jurusan harus menyediakan sarana dan prasarana yang relevan dan mutakhir seperti laboratorium, bengkel, studio, dan yang lainnya untuk menunjang kegiatan pengabdian kepada masyarakat terkait dengan bidang ilmu program studi, proses pembelajaran, dan kegiatan pengabdian kepada masyarakat yang sesuai dengan kebutuhan dosen dan mahasiswa, termasuk yang berkebutuhan khusus
','Universitas/Fakultas/PPs/Lembaga/Jurusan harus memiliki sistem informasi untuk layanan pengabdian kepada masyarakat yang terbukti efektif memenuhi aspek-aspek berikut: 1) ketersediaan layanan elearning, perpustakaan (e-journal, ebook, e-repository, dll.), 2) mudah diakses oleh sivitas akademika, dan 3) seluruh jenis layanan dievaluasi secara berkala yang hasilnya ditindak lanjuti untuk penyempurnaan sistem informasi
','Universitas/Fakultas/PPs/Lembaga/Jurusan harus harus memiliki sistem informasi untuk layanan administrasi pengabdian kepada masyarakat yang terbukti efektif memenuhi aspek-aspek berikut: 1) mencakup layanan akademik, keuangan, SDM, dan sarana dan prasarana (aset), 2) mudah diakses oleh seluruh unit kerja dalam lingkup institusi, 3) lengkap dan mutakhir, 4) seluruh jenis layanan telah terintegrasi dan digunakan untuk pengambilan keputusan, dan 5) seluruh jenis layanan yang terintegrasi dievaluasi secara berkala dan hasilnya ditindak lanjuti untuk penyempurnaan sistem informasi
','Universitas/Fakultas/PPs/Lembaga/Jurusan harus melakukan evaluasi kecukupan sarana dan prasarana untuk PkM memenuhi standar mutu, keselamatan kerja, kesehatan, kenyamanan, dan keamanan.
  ','  Universitas/Fakultas/PPs/Lembaga/Jurusan secara periodik harus merawat dan memelihara sarana dan prasarana untuk kebutuhan pengabdian kepada masyarakat
','Universitas/Fakultas/PPs/Lembaga/Jurusan secara periodik harus melakukan peningkatan sarana dan prasarana guna mendorong dan memfasilitasi dosen untuk melakukan kerja sama dalam melaksanakan kegiatan pengabdian kepada masyarakat dengan universitas atau lembaga lain, baik di lingkup nasional maupun internasional.
'],['Universitas harus membentuk lembaga penelitian dan pengabdian secara terintegrasi untuk mengelola penelitian dan pengabdian kepada masyarakat untuk menunjang visi UNAI menjadi universitas berkelas dunia.
    ','Universitas harus menyusun dan menetapkan Rencana Strategis Pengabdian kepada Masyarakat (Renstra PkM) yang memuat landasan pengembangan, garis besar program, kegiatan dan indikator kinerja, pola pelaksanaan, pemantauan dan evaluasi sebagai pedoman dan acuan pengabdian civitas akademika.
    ','Universitas melaksanakan penerapan akreditasi internasional sebagai berikut pengakuan eksternal, yang memberikan jaminan terhadap pengelolaan yang tertata dan tertib, serta mempermudah pencapaian sasaran dan target pengabdian.
    ','Universitas harus menyusun Buku Panduan Pengabdian kepada Masyarakat di lingkungan Universitas Advent Indonesia dengan mengacu pada Panduan Pelaksanaan Pengabdian kepada Masyarakat Direktorat penelitian dan Pengabdian Kepada Masyarakat, Kementerian Riset, Teknologi, dan Pendidikan Tinggi.
    ','Universitas harus menyusun Buku Panduan PkM yang memuat tentang cara atau prosedur pengajuan proposal, pelaksanaan, dan pelaporan berbagai jenis kegiatan PkM dan disosialisasikan ke semua dosen.
    ','Universitas harus melaksanakan pengelolaan pendanaan pengabdian secara terintegrasi agar kegiatan pengabdian dapat selaras dengan Renstra PkM Universitas
','Universitas harus menerbitkan agenda kegiatan pengabdian PkM dalam jangka pendek, jangka menengah, dan jangka panjang sesuai visi, misi, dan tujuan universitas.
  ','  Universitas harus melakukan pemantauan terhadap perencanaan, pelaksanaan, pengandalian, pemantauan, dan evaluasi kegiatan PkM.
    ','Universitas harus memberikan penawaran kegiatan PkM kepada dosen dilengkapi jadwal kegiatan yang rinci dan realistik, meliputi jadwal pengajuan proposal, penilaian proposal, pengumuman penerimaan, seminar awal, pelaksanaan kegiatan, monitoring, seminar akhir, dan batas akhir pengumpulan laporan.
    ','Universitas harus menunjuk reviewer, pembahasan, dan penilaia pada kegiatan penilaian proposal, seminar awal dan hasil kegiatan pengabdian kepada masyarakat, yang memiliki kompetensi akademik sesuai dengan bidang kegiatan pengabdian.
    ','Universitas menetapkan penggunaan teknologi informasi (TI) pengelolaan kegiatan pengabdian kepada masyarakat, sehingga tercipta pengelolaan yang tertib dan akuntabel.
    ','Universitas harus membuat laporan kinerja pelaksanaan PkM kepada pangkalan data dikti, universitas, dan lembaga mitra secara komprehensif secara berkala (triwulan, semester, dan tahunan).
',
'Universitas mewajibkan setiap dosen di UNAI untuk melakukan kegiatan PkM minimal sekali dalam setahun, baik mandiri, tim, atau melibatkan mahasiswa dengan mematuhi kaidah/norma dan etika akademik sesuai dengan prinsip otonomi keilmuan.
',
'Universitas mewajibkan kelompok pelaksana pengabdian menghasilkan produk PkM yang memiliki daya saing tinggi dalam rangka memecahkan permasalahan masyarakat secara tepat.
'    ,'Universitas harus menyelenggarakan kegiatan berkala untuk meningkatkan kualitas PkM dosen UNAI seperti pelatihan/workshop dalam hal kualitas proposal, kualitas pelaksanaan proses kegiatan PkM, dan kualitas penulisan artikel hasil kegiatan PkM dosen dan/atau mahasiswa UNAI.
',
'Universitas harus memberikan penghargaan terhadap hasil pengabdian kepada masyarakat yang ditulis menjadi karya ilmiah dan dipublikasikan dalam publikasi terindeks internasional.
',    'Universitas mewajibkan setiap pengabdi untuk menjalin kerjasama dengan mitra yang ditandai dengan surat pernyataan sebagai mitra sebagai salah satu unsur penting dalam kinerja pengabdian.
'],[
                'Universitas harus menetapkan dana PkM untuk dosen setiap tahun
','Universitas harus membiayai kegiatan PkM dosen yang proposalnya dianggap layak
','Universitas harus mengevaluasi serapan dana PkM setiap akhir tahun.
  ','  Universitas harus memonitor serapan dana PkM dosen sebelum waktu PkM selesai.
    ','Universitas harus berusaha meningkatkan besarnya dana PkM untuk dosen setiap tahun.
      '      ],
            ['Penetapan standar keunggulan UNAI memenuhi hal-hal: 1) bidang pengajaran, penelitian, PkM, kemahasiswaan, serta tata kelola dan budaya UNAI; 2) Uraian kriteria pelaksanaan secara berkelanjutan.
    ','Keterlaksanaan standar keunggulan sesuai dengan kebijakan.
    ','Ketersediaan bukti pengukuran kepuasan pengguna (internal dan eksternal) pada bidang akademik dan non akademik yang memenuhi berbagai aspek.
    ','Pemantauan dan evaluasi pelaksanaan secara berkelanjutan',
'Adanya peningkatan standar UNAI unggul.'],[
                        'Universitas harus membentuk unit pengelola penerimaan mahasiswa baru untuk mengelola penerimaan mahasiswa baru pada semua jenjang studi dan jalur penerimaan setiap tahun.
            ','Universitas harus memiliki panduan rekrutmen dan sistem seleksi mahasiswa baru untuk semua jalur dan semua jenjang studi yang diperbaharui setiap tahun.
            ','Unit pengelola penerimaan mahasiswa baru harus melakukan sosialisasi rekrutmen mahasiswa baru melalui situs UNAI agar tercapai rasio jumlah lulus seleksi pendaftar terhadap jumlah pendaftar untuk program sarjana dan pascasarjana tidak lebih dari 90%.
        ','Unit pengelola penerimaan mahasiswa baru harus melakukan sosialisasi rekrutmen mahasiswa baru ditingkat lokal dan nasional agar tercapai persentase jumlah mahasiswa yang mendaftar ulang terhadap jumlah pendaftar yang lulus seleksi lebih besar atau sama dengan 95%.
        ','Program studi harus bekerjasama dengan pihak terkait untuk melakukan upaya peningkatan animo calon mahasiswa baru agar persentase pendaftar dalam 3 tahun terakhir mengalami peningkatan lebih besar dari 9%.
        ','Unit pengelola penerimaan mahasiswa baru harus melakukan sosialisasi rektrutmen mahasiswa baru di tingkat internasional agar persentase jumlah mahasiswa asing terhadap jumlah seluruh mahasiswa aktif lebih besar atau sama dengan 1,5%.
        ','Unit pengelola penerimaan mahasiswa baru harus bekerjasama dengan pihak terkait melakjkan evaluasi pelaksanaan program penerimaan mahasiswa baru setiap tahun.
          '      ,
                'Universitas menyediakan wahana pembinaan minat dan bakat mahasiswa dalam bidang penalaran, seni, olahraga, dan kesejahteraan, dan minat khusus setiap tahun.
    ','Universitas melaksanakan pembinaan karakter, pembinaan mental kebangsaan, dan pengembangan soft skilss mahasiswa secara berkesinambungan setiap tahun.
    ','Universitas memfasilitasi pengembangan minat dan bakat mahasiswa dalam bidang penalaran, seni, olahraga, dan kesejahteraan dan minat khusus melalui penyediaan tenaga pembina, pembimbing, dan pendamping kegiatan kemahasiswaan setiap tahun.
    ','Universitas menyediakan sistem informasi untuk pelaporan prestasi mahasiswa dan memberikan apresiasi setiap tahun bagi mahasiswa yang berprestasi dalam bidang penalaran, seni, olahraga, dan kesejahteraan dan minat khusus sesuai dengan ketentuan yang telah ditetapkan.
    ','Universitas memberikan layanan kesejahteraan bagi mahasiswa melalui layanan bimbingan karir secara periodik setiap tahun minimal 4 kali.
    ','Universitas memberikan layanan kesejahteraan bagi mahasiswa melalui layanan bimbingan kewirausahaan secara periodik setiap tahun minimal 2 kali setiap program kewirausahaan.
    ','Universitas memberikan layanan kesejahteraan bagi mahasiswa melalui layanan kesehatan dan layanan bimbingan dan konseling sesuai kebutuhan mahasiswa setiap tahun.
    ','Universitas memberikan layanan kesejahteraan mahasiswa melalui program beasiswa baik dari pemerintah maupun dunia usaha dan dunia industri (DUDI) setiap tahun.
    ','Universitas melakukan audit dan evaluasi terhadap kinerja organisasi mahasiswa, pelaksanaan kegiatan, dan capaian prestasi secara periodik dan komprehensif setiap tahun.
      ','Universitas memiliki pedoman organisasi kemahasiswaan sebagai pedoman organisasi mahasiswa menjalankan kegiatan dan program kerja selama satu tahun.
    ','Universitas memfasilitasi sarana dan prasaran pendukung operasional organisasi kemahasiswaan setiap tahun dengan mematuhi pedoman organisasi kemahasiswaan yang telah ditetapkan.
    ','Universitas memfasilitasi organisasi kemahasiswaan membuat laporan pertanggung jawaban pelaksanaan kegiatan, capaian prestasi, dan pelaksanaan monitoring dan evaluasi setiap tahun sesuai dengan prosedur operasional baku yang ditetapkan. '
        ,'Universitas memiliki pedoman untuk pengembangan nalar, minat, dan bakat, peningkatan kesejahteraan, layanan beasiswa, layanan kesehatan, bimbingan karir, bimbingan kewirausahaan, layanan bimbingan dan konseling, pengembangansoft skills, dan layanan akses internet mahasiswa (Layanan internet berkabel, layanan Wifi, dan layanan video streaming) setiap tahun.
        ','Universitas memiliki pedoman pengembangan organisasi alumni sebagai pedoman pelaksanaan kegiatan pengembagnan setiap tahun.
    ','Universitas memfasilitasi organisasi alumni untuk meningkatkan perannya dalam masyarakat setiap tahun.
    ','Univeristas bersama DPP IKA UNAI melakukan pembinaan alumni untuk bersinergi dengan almamater mengembangkan almamater setiap tahun.
      ',',Universitas mempunyai program pemberdayaan alumni melalui kegiatan Ikatan Alumni UNAI.
    ','Universitas memberdayakan alumni dalam pengembangan perguruan tinggi dengan memberikan kontribusi dana setelah dinyatakan lulus, dan temu alumni pengembangan jejaring setiap tahun.
    ','Univeristas melalui bidang kemahasiswaan dan alumni bersama dengan IKA UNAI menyelenggarakan kegiatan evaluasi dan tindak lanjut untuk pengembangan alumni setiap empat tahun sekali.
    ','Universitas membantu pengembangan karir alumni secara periodik minimal 1 kali pelatihan dalam setahun.'  ], [
'                Visi, Misi dan Tujuan UNAI dirumuskan oleh Yayasan, Rektorat dan pihak-pihak yang berkepentingan utama yang selaras satu dengan lainnya.
','    Pimpinan Universitas merumuskan RPJP, RPJM/Renstra, dan Renop yang memuat sasaran, strategi pencapaian. Program kerja, kegiatan, indikator kinerja dan pentahapan pencapaian targetnya untuk mengukur ketercapaian tujuan strategis dalam visi-misi yang telah ditetapkan.
','  Pimpinan Universitas merumuskan RPJP, RPJM/Renstra, dan Renop, tonggak-tonggak capaian (milestones) UNAI setiap lima tahun.
  ','  Visi menjadi kekuatan pembimbing yang terpatri dalam pikiran dan tindakan segenap pihak yang berkepentingan.
    '        ],[
                'Universitas harus menetapkan Panduan Operasional Baku pelaksanaan kerja sama yang dijadikan pegangan oleh pengella universitas, fakultas, program pascasarjana, lembaga, jurusan, dan program studi untuk menjalin kerja sama dengan berbagai pihak, baik pemerintah maupun swasta, nasional maupun internasional.
    ','Operasional pelaksanaan kerja sama mengacu pada panduan yang berisi arahan, prosedur, tata cara dan persyaratan bagi unit kerja di lingkungan UNAI untuk menjalin kerja sama dengan lembaga mitra.
    ','Universitas harus menyusun perencanaan pengembangan jejaring dan kemitraan yang sahih dan terarah guna mencapai visi, misi, dan tujuan strategis institusi.
    ','Pelaksanaan kerja sama di UNAI harus dilengkapi dengan dokumen kerja sama dalam bentuk nota kesepahaman (Memorandum of Understanding?MoU), surat perjanjian kerja sama (Memorandum of Agreement/MoA), dan Letter of Intent (LoI), atau nama lain sesuai kesepakatan.
    ','Lembaga, Unit Pelaksana Teknis, fakultas, program studi, atau unit kerja merealisasikan pelaksanaan kerja sama sesuai nota kesepahaman.
    ','Universitas harus memperluas kerjasama dengan dunia usaha dan dunia industri untuk kepentingan akademik atau non akademik.
    ','Universitas harus mengevaluasi implementasi kerja sama yang relevan dengan VMTS dan bermanfaat bagi pengembangan Tridarma institusi yang mencakup kerja sama: a) lokal/wilayah. B) nasional. C) internasional.
    ','Universitas harus melakukan peninjauan hasil evaluasi pelaksanaan kerjasama untuk perbaikan mutu jejaring dan kemitraan yang berkelanjutan, untuk menjamin terwujudnya visi, terlaksanaanya misi dan tercapainya tujuan strategis.
      '      ],['Universitas harus menetapkan panduan operasional baku pelaksanaan pengelolaan sumber daya manusia yang dijadikan pegangan oleh pengelola universitas, fakultas, program pasca sarjana, lembaga, dan program studi untuk menghasilkan sumber daya manusia sesuai dengan visi, misi UNAI.
    ','Operasional pelaksanaan pengelolaan sumber daya manusia mengacu pada panduan yang berisi arahan, prosedur, tata cara dan persyaratan bagi unit kerja di lingkungan UNAI untuk melakukan pengelolaan sumber daya manusia.
    ','Universitas harus menyusun perencanaan pengembangan pengelolaan sumber daya manusia yang sahih dan terarah guna mencapai visi, misi, dan tujuan strategis iinstitusi.
    ','pelaksanaan pengelolaan sumber daya manusia di UNAI harus dilengkapi dengan dokumen pengelolaan sumber daya manusia.
    ','Lembaga, unit pelaksana teknis, fakultas, program studi, atau unit kerja merealisasikan pelaksanaan pengelolaan sumber daya manusia sesuai dengan yang diharapkan.
    ','Universitas harus meningkatkan sumber daya manusia sesuai dengan keadaan perkembangan zaman untuk kepentingan akademik atau non akademik.
    ','Univeristas harus mengevaluasi implementasi pengelolaan sumber daya manusia yang relevan dengan VMTS dan bermanfaat bagi pengembangan Tridarma.
    ','Universitas harus melakukan peninjauan hasil evaluasi pelaksanaan pengelolaan sumber daya manusia untuk perbaikan mutu SDM, untuk menjamin terwujudnya visi, terlaksananya misi dan tercapainya tujuan strategis. '],
            [
               ' UNAI memiliki dokumen formal tata pamong yang dijabarkan ke dalam berbagai kebijakan dan peraturan yang digunakan secara konsisten, efektif, dan efisien sesuai konteks institusi serta menjamin akuntabilitas, keberlanjutan, transparansi, dan mitigasi potensi risiko, yang menjamin terwujudnya visi, terlaksanya misi, tercapainya tujuan, berhasilnya strategi yang digunakan.
    ','UNAI memiliki bukti yang sahih (dokumen formal kebijakan dan peraturan) guna menjamin integritas dan kualitas institusi yang dilaksanakan secara konsisten, efektif dan efisien.
    ','UNAI memiliki struktur organisasi yang dituangkan lengkap yang terdiri dari unsur yang disesuaikan dengan kebutuhan penyelenggaraan dan pengembangan perguruan tinggi yang bermutu, yang terdiri minimal terdiri dari: a) penyusunan kebijakan; b) pelaksana akademik; c) pengawas dan penjaminan mutu; d) penunjang akademik atau sumber belajar; dan e) pelaksana administrasi atau tata usaha. dan telah disesuaikan dengan Statuta UNAI. Dimana Statuta UNAI paling sedikit memuat: a) ketentuan umum; b) identitas; c) penyelenggaraan Tridharma Perguruan Tinggi; d) sistem pengelolaan; e) sistem penjaminan mutu internal; f) bentuk dan tata cara penetapan peraturan; g) pendanaan dan kekayaan; h) ketentuan peralihan; dan i) ketentuan penutup.
    ','UNAI memiliki dokumen formal struktur organisasi dan tata kerja institusi yang jelas tentang tugas pokok dan fungsi (tupoksi) guna menjamin terlaksananya fungsi perguruan tinggi secara konsisten, efektif, dan efisien.
    ','UNAI memiliki bukti yang sahih terkait praktik baik perwujudan GUG mencakup aspek: kredibilitas, transparansi, akuntabilitas, tanggung jawab, keadilan, dan manajemen risiko secara konsisten, efektif dan efisien.
    ','UNAI mengumumkan ringkasan laporan tahunan kepada masyarakat.
    ','UNAI memiliki lembaga yang sepenuhnya melaksanakan atau berfungsi dalam penegakan kode etik untuk menjamin tata nilai dan integritas secara konsisten, efektif, dan efisien.
    ','UNAI memiliki dokumen formal penetapan personil pada berbagai tingkat manajemen dengan tugas dan tanggung jawab yang jelas, rinci, dan konsisten terhadap pencapaian visi, misi dan budaya serta tujuan strategis institusi.
    ','Deskripsi tertulis yang jelas tentang tugas pokok dan fungsi untuk seluruh pejabat struktural maupun staff harus disosialisasikan kepada semua pihak terkait, paling lambat saat pengangkatan pejabat struktural ataupun staf.
    ','UNAI memiliki dokumen yang menunjukkan sumber daya yang akan dialokasikan untuk mencapai Standar UNAI yang telah ditetapkan, yang telah mempertimbangkan manajemen risiko untuk menjamin keberlangsungan perguruan tinggi, serta mekanisme kontrol pencapaiannya.
    ','Setiap pimpinan di UNAi harus memiliki karakteristik kepemimpinan yang efektif yang mampu mengarahkan dan mempengaruhi perilaku semua unsur di UNAI sebagai institusi perguruan tinggi untuk mengikuti nilai, normal, etika, dan budaya organisasi yang disepakati bersama serta mampu membuat keputusan yang tepat dan cepat, yang meliputi: a) kepemimpinan opersional, b) kepemimpinan organisasi, c) kepemimpinan publik.
    ','Setiap pimpinan di UNAI harus memiliki kepemimpinan yang mampu memprediksi masa depan, merumuskan dan mengartikulasi visi yang realistik, kredibel, serta mengkomunikasikan visi ke depan yang menekankan pada keharmonisan hubungan manusia dan mampu menstimulasi secara intelektual dan arif bagi anggota untuk mewujudkan visi organisasi, serta mampu memberikan arahan, tujuan, peran, dan tugas kepada seluruh unsur dalam institusi perguruan tinggi.
    ','UNAI memiliki bukti yang terdokumentasi dan sahih terkait terjalinnya komunikasi yang baik antara pimpinan dan stakeholders internal yang dilakukan secara terprogram dan intensif untuk mendorong tercapainya visi, misi, budaya, dan tujuan strategis institusi.
    ','UNAI memiliki bukti yang terdokumentasi dan sahih tentang telaah yang komprehensif dan perbaikan secara efektif terhadap pelaksanaan kepemimpinan dan personil pada berbagai tingkatan manajemen untuk mencapai kinerja organisasi yang direncanakan.
    ','UNAI memiliki bukti formal keberfungsian sistem pengelolaan fungsional dan operasional perguruan tinggi yagn mencakup 5 aspek yang dilaksanakan secara konsisten, efektif, dan efisien meliputi: a) perencanaan (planning), b) pengorganisasian (organizing), c) penempatan personil (staffing), d) pengarahan (leading), dan e) pengawasan (controlling).
    ','UNAI memiliki dokumen formal dan pedoman pengelolaan yang rinci dan memiiliki kesesuaian mencakup aspek: a) pendidikan, b) pengembangan suasana akademik dan otonomi keilmuan, c) kemahasiswaan, d) penelitian, e) PkM, f) SDM, g) keuangan, h) sarana dan prasarana, i) sistem penjaminan mutu, dan j) kerjasama.
    ','UNAI memiliki bukti yang sahih tentang implementasi kebijakan dan pedoman pengelolaan yang mencakup 11 aspek: a) pendidikan, b) pengembangan suasana akademik dan otonomi keilmuan, c) kemahasiswaan, d) penelitian, e) PkM, f) SDM, g) keuangan, h) sarana dan prasarana, i) sistem penjaminan mutu, dan j) kerjasama.
    ','UNAI memiliki dokumen formal Rencana Strategis (yang mencakup perencanaan finansial dan sumber daya, pengelolaan dan pengendalian risiko, kepatuhan terhadap peraturan, konflik kepentingan, pelaporan dan audit), dan bukti mekanisme persetujuan dan penetapan yang mencakup 5 aspek sebagai berikut: a) adanya keterlibatan pemangku kepengtingan, b) mengacu kepada capaian renstra periode sebelumnya, mengacu kepada VMTS institusi, d) dilakukannya analisi kondisi internal dan eksternal, dan disahkan oleh organ yang memiliki kewenangan.
    ','UNAI memiliki rencana implementsi penjaminan mutu yang mencakup: strategi, kebijakan, pemberdayaan para pemangku kepentingan yang merupakan bagian dari rencana jangka menengah maupun jangka panjang.
    ','UNAI memiliki dokumen formal pengembangan sistem penjaminan mutu UNAI.
    ','UNAI menjalankan SPMI untuk seluruh aras (universitas, fakultas, program studi, dan seluruh unit pendukung pada seluruh aras tersebut) yang dibuktikan dengan keberadaan 5 aspek, memiliki standar yang melampaui dari SNDIKTI, dan menerapkan SPMI berbasis resiko (Risk Based Audit) atau inovasi lainnya dimana 5 aspek tersebut adalah: a) organ/fungsi SPMI, yang dibutkikan dengan adanya dokumen formal pembentukan unsur pelaksana penjaminan mutu internal di perguruan tinggi b) dokumen SPMI (pernyataan komitmen mutu, kebijakan mutu, standar mutu, manual mutu dan dokumen lain yang diperlukan). c) auditor internal, yang dibuktikan dengan adanya dokumen formal pembentukan auditor internal dan surat tugas audit. d) hasil audit dan bukti tindak lanjut.
    ','UNAI memiliki sistem perekaman dan dokumentasi mutu, serta publikasi hasil penjaminan mutu internal kepada para pemangku kepentingan untuk seluruh aras implementasi SPMI (universitas, fakultas, prodi, maupun unit pendukung pada aras tersebut).
','UNAI memiliki pengembangan budaya mutu di perguruan tinggi melalui rapat tinjauan manajemen, yang mengagendakan pembahasan 7 unsur, yaitu: a) hasil audit internal, b) umpan balik, c) kinerja proses dan kesesuaian produk, d) status tindakan pencegahan dan perbaikan, e) tindak lanjut dari tinjauan sebelumnya, f) perubahan yang dapat mempengaruhi sistem manajemen mutu, dan g) rekomendasi untuk peningkatan.
  ','  UNAI di tingkat universitas memperoleh sertifikasi/akreditasi eksternal oleh lembaga internasional atau internasional bereputasi.
   ',' UNAi di tingkat program studi memperoleh sertifikasi/akreditasi eksternal oleh lembaga internasional atau internasional bereputasi
','UNAI melaksanakan audit eksternal keuangan terhadap Yayasan, oleh kantor Akuntan Publik
','UNAI memiliki standar mutu yang melampaui SN-DIKTI dan memiliki daya saing internasional, yang ditunjukkan adanya indikator kinerja tambahan yang ditetapkan. Data indikator kinerja tambahan telah diukur, dimonitor, dikaji, dan dianalisis untuk perbaikan berkelanjutan.
  ','  UNAI melaksanakan setiap tahun dan hasilnya dipublikasikan kepada para pemangku kepentingan. A) capaian kinerja harus diukur dengan metoda yang tepat, dan hasilnya dianalisis serta dievaluasi, dan b) analisis terhadap capaian kinerja mencakup identifikasi akar masalah, faktor pendukung keberhasilan dan faktor penghambat ketercapaian standard, dan deskripsi singkat tindak lanjut yang akan dilakukan institusi.
    ','UNAI telah melaksanakan sistem penjaminan mutu yang terbukti efektif memenuhi 4 aspek dan dilakukan review terhadap siklus penjaminan mutu yang melibatkan reviewer eksternal. Adapun efektivitas pelaksanaan sistem penjaminan mutu UNAI memenuhi 4 aspek sebagai berikut: a) keberadaan dokumen formal penetapan standar mutu, b) standar mutu dilaksanakan secara konsisten, monitoring, c) evaluasi dan pengendalian terhadap standar mutu yang telah ditetapkan, d) hasilnya ditindak lanjuti untuk perbaikan dan peningkatan mutu.
    ','UNAI melaksanakan pengukuran kepuasan pemangku kepentingan internal dan eksternal di UNAI pada masing-masing kriteria (tata pamong dan kerjasama, mahasiswa, sumber daya manusia, keuangan, sarana dan prasana, pendidikan penelitian dan pengabdian kepada masyarakat) yang memenuhi 4 aspek, hasilnya dipublikasi serta mudah diakses oleh kepentingan, dan dilakukan review terhadap pelaksanaan pengukuran kepuasan pengguna. Adapun 4 aspek tersebut adalah sebagai berikut: a) menggunakan isntrumen kepuasan yang sahih, andal, mudah digunakan, b) dilaksanakan secara berkala, serta datanya terekam secara komprehensif, c) dianalisis dengan metode yang tepat serta bermanfaat untuk pengambilan keputusan, d) tingkat kepuasan dan umpan balik ditindaklanjuti untuk perbaikan dan peningkatan mutu luaran secara berkala dan tersistem.
      '      ],[
                'UNAI sebagai penyelenggara RPL harus memiliki legalitas sebagai penyelenggara pendidikan tinggi sebagaimana ditentukan dalam peraturan pemerintah Nomor 4 tahun 2014 tentang penyelenggaraan pengelolan perguruan tinggi.
    ','UNAI sebagai penyelenggara RPL harus menjamin setiap individu dalam mengakses kesempatan belajar secara berkeadilan dan inklusifitas. Setiap individu memiliki hak untuk mengakses dan terlibat dalam segala pembelajaran yang sesuai dengan kebutuhannya dengan capaian pembelajaran yang memenuhi SN DIKTI.
    ',"UNAI sebaga penyelenggara RPL harus memberikan penilaian yang setara atas capaian pembelajaran yang diperoleh dari pendidikan formal, non-formal, informal, dan/atau pengelaman kerja. Akumulasi capaian pembelajaran setiap individu yang diperoleh dari pendidikan non-formal, informal, dan'atau pengalaman kerja harus diperlaukan setara dengan mereka yang diperoleh melalui pembelajaran formal.
",'Perguruan tinggi sebagai penyelenggara RPL harus menyediakan informasi mengenai RPL yang harus diumumkan secara luas dan terbuka dengan menggunakan bahasa yang jelas dan eksplisit agar dapat dipahami oleh semua pemangku kepentingan (permohon, perguruan tinggi penyelenggara, lembaga akreditasi, dan pengguna lulusan). Kebijakan, proses, dan kriteria sepenuhnya diungkapkan secara lengkap akurat, dan terbuka bagi publik
','UNAI sebagai penyelenggara RPL harus menjamin mutu seluruh pelaksana RPL. Kriteria dan prosedur untuk menilai dan memvalidasi capaian pembelajaran yang diperoleh dari pendidikan non formal, informal, dan/atau pengalaman kerja harus relevan, terpercaya, adil dan transparan. Kebijakan, prosedur dan proses penjaminan mutu RPL dibuat eksplisit dan terbuka untuk publik.
','UNAI sebagai penyelenggara RPL harus memiliki: a) Senat Perguruan Tinggi telah berfungsi dan berperan dengan baik, b) SPMI yang telah berfungsi dengan baik; c) Tim penyelenggara RPL yang dibentuk pada tingkat perguruan tinggi/Fakultas/ Departemen/Jurusan yang dapat bersifat adhoc atau tetap. Tim minimal terdiri atas: 1) penasehat akademik khusus RPL yang memahami konsep, paradigma, peraturan dan mekanisme pelaksanaan RPL; dan 2) Asesor RPL yang memahami konsep, paradigma, peraturan dan mekanisme pelaksanaan asesmen dan area pengetahuan (body of knowledge) sesuai program studi dimana pengakuan capaian pembelajaran akan dilaksanakan. Asesor RPL adalah staf pengajar dan dapat melibatkan praktisi industri atau anggota asosiasi profesi yang sesuai bidang keahlian yang berhubungan dengan aplikasi yang disampaikan pemohon.
'
            ],[
               ' Penerimaan Mahasiswa Baru Universitas Advent Indonesia diselenggarakan dengan prinsip; adil dan tidak diskriminatif yaitu tidak membedakan asal daerah calon mahasiswa, jenis kelamin, suku, agama, ras, antar golongan (SARA), umur, kedudukan sosial, latar belakang politik, dan tingkat kemampuan ekonomi calon mahasiswa, dengan tetap memperhatikan potensi calon mahasiswa.
    ','UNAI membuka pendaftaran mahasiswa melalui online system pada jangka waktu yang telah disepakati.
    ','UNAI melakukan promosi penerimaan mahasiswa baru minimal 6 bulan sebelum tahun akademik baru dimulai.
    ','Universitas Advent Indonesia memberikan kebijakan terhadap Calon Mahasiswa baru yang memiliki potensi akademik dan non akademik namun mukran mampu secara ekonomi maka kepada calon mahasiswa tersebut dapat diberikan bantuan biaya pendidikan/beasiswa yang jumlahnya bervariasi sesuai dengan kemampuan dan kondisi keuangan institusi.
    ','UNAI memberikan kebijakan untuk calon mahasiswa yang menyandang disabilitas atau cacat fisik tetap diperkenankan untuk mengikuti seleksi Penerimaan Mahasiswa Baru yang diselenggarakan oleh Universitas Advent Indonesia selama ada motivasi dan kemauan yang kuat dari calon mahasiswa bersangkutan untuk menyelesaikan studinya di Universitas Advent Indonesia.
    ','Seluruh proses penerimaan mahasiswa baru (promosi, pendaftaran, seleksi, dll) pada semua program studi program sarjana dipusatkan di tingkat Rektorat
','Seluruh kegiatan penerimaan mahasiswa baru (promosi, pendaftaran, seleksi, dll) dilaksanakan oleh Panitia Penerimaan Mahasiswa Baru (PPMB) yang ditunjuk dan diangkat oleh Rektor Universitas Advent Indonesia dengan Surat Keputusan (SK) Rektor.
  ','  Masa Jabatan Panitia Penerimaan Mahasiswa Baru (PMB) Universitas Advent Indonesia adalah 1 (satu) tahun akademik, dan setelahnya dapat dipilih/diangkat kembali.
    ','Penetapan calon mahasiswa baru yang telah lolos seleksi dan ditermia menjadi mahasiswa UNAI dilakukan melalui Surat Keputusan (SK) Rektor Universitas Advent Indonesia. Apabila terdapat penerimaan mahasiswa baru tidak melalui Surat Keputusan (SK) Rektor, maka status mahasiswa tersebut dinyatakan tidak sah.
    ','Penyusunan Program Kerja dan Penggunaan Anggaran Panitia Penerimaan Mahasiswa Baru (PMB) Universitas Advent Indonesia mengikuti prosedur pada pedoman Penerimaan Mahasiswa Baru 9PMB).
      '      ],
            ['Kepala perpustakaan, sesuai lingkupnya memastikan bahwa koleksi perpustakaan mengacu pada seluruh kebutuhan referensi program studi (prodi) yang dinyatakan dalam Rencana Pembelajaran Semester (RPS) masing-masing mata kuliah serta kebutuhan sivitas akademik UNAI.
    ','Kepala Perpustakaan, Kaprodi dan Kabab Pengadaan dan Pengolahan sesuai lingkupnya memastikan bahwa penyediaan koleksi untuk memenuhi kebutuhan jumlah buku wajib permata kuliah minimal 3 (tiga) judul dan judul buku pengayaan 2 kali jumlah buku wajib atau 6 judul per mata kuliah. (SNPPT No 13 Tahun 2017).
','Kepala Perpustakaan memastikan tersedianya referensi setiap prodi yaitu: a) Buku teks untuk setiap bidang ilmu minimal 600 judul, yang menjamin kecukupan ketersediaan minimal 400 judul buku per prodi. B) Jurnal Nasional terkareditasi setiap bidang ilmu minimal 3 judul dan nomornya lengkap, kecuali untuk bidang ilmu yang belum memiliki jurnal terakreditasi. c) Jurnal Internasional setiap bidang ilmu minimal 2 judul dan nomornya lengkap baikberupa tercetak maupun online. d) Prosiditng setiap bidang ilmu minimal 9 judul baik berupa tercetak maupun online. d) Prosiding setiap bidang ilmu minimal 9 judul baik berupa tercetak maupun online. e) Tugas akhir/tesis/skripsi untuk prodi minimal 10 judul.
  ','Kepala Perpustakaan dan Kabag Pengadaan dan Pengolahan sesuai lingkupnya menjamin adanya pengembangan koleks paling sedikit 3,5% per tahun dari jumlah koleksi buku yang ada.
    ','Kepala Perpustakaan dan Kabag Pengadaan dan Pengolahan sesuai lingkupnya memastikan pengolahan bahan perpustakaan dideskripsikan, diklasifikasi, diberi tajuk subjek dan disusun secara sistematis dengan sistem baku.
    ','Kepaka Perpustakaan, Direktur IT dan Kabab Pengadaan dan Pengolahan sesuai lingkupnya memastikan bahwa sistem informasi perpustakaan dapat menampilan daftar buku per prodi.
    '.'Kepala Perpustakaan, Direktur IT dan Kabag Pengadaan dan Pengolahan sesuai lingkupnya memastikan bahwa repositori institusi telah dikembangkan dan dapat diakses melalui website repositori.
     '   ],[
                'Ruang kerja dosen tetap memenuhi kelayakan dan mutu melakukan aktivitas kerja, pengembangan diri, dan pelayanan akademik.
    ','Prasarana yang dipergunakan program studi dalam proses pembelajaran lengkap dan mutunya sangat baik.
    ','Prasarana penunjang untuk memenuhi kebutuhan mahasiswa lengkap dan mutunya sangat baik.
    ','Pengadaan barang investasi (prasarana dan sarana) berlangsung tepat waktu dengan harga wajar serta spesifikasi, kuantitas, dan kualitas barang investasi (prasarana dan sarana) yang diadakan sesuai dengan yang direncanakan.
    ','Pengadaan barang investasi akuntabel.
      '      ],[
             '   Rektor Universitas Advent Indonesia harus menyediakan dokumen kebijakan, mekanisme, prosedur dan pedoman tertulis mengenai gaji poko, tunjangan yang melekat pada gaji, serta penghasilan lain yang berupa tunjangan profesi, tunjangan fungsional, tunjangan khusus, tunjangan kehormatan, remunerasi, Tunjangan Hari raya, gaji 13 serta maslahat tambahan dalam bentuk rumah dinas dan atau fasilitas bantuan pengadaan rumah, tunjangan pendidik, beasiswa, dan penghargaan bagi dosen dan Tendi, serta keringanan UKTB dan beasiswa bagi anak dosen dan Tendik, pelayanan kesehatan, promosi dan penghargaan, jaminan perlindungan hukum, memperoleh cuti dan rekreasi, pengangkatan, penempatan, pemindahan, pemberhentian, pembinaan dan pengembangan diri serta sanksi terhadap dosen dan tendik sesuai dengan peraturan perundang-undangan yang berlaku atau bentuk kesejahteraan lain, jaminan sosial kesehatan/tenaga kerja sesuai dengan peraturan perundang-undangan yang berlaku atau bentuk kesejahteraan lain, jaminan sosial kesehatan/tenaga kerja sesuai dengan peraturan perundang-undangan meliputi iuran BPJS: a) jaminan kesehatan termasuk keluarganya. b) jaminan kecelakaan kerja. c) jaminan hari tua. d) jaminan pensiun, dan e) santunan kematian dalam rangka memenuhi kesejahteraan dosen dan tendik yang disusun secara berkala sesuai ketentuan yang berlaku.
    ','Rektor melalui wakil rektor bidang keuangan harus mengupayakan pendanaan dalam rangka memenuhi kesejahteraan dosen dan tendik dari berbagai sumber.
    ','Rektor melalui wakil rektor bidang keuangan harus menyediakan pedoman tertulis tentang kesejahteraan dosen dan tenaga kependidikan.
    ','Rektor melalui wakil rektor bidang keuangan dekan bidang keuangan harus melakukan sosialisasi standar kesejahteraan dosen dan tenaga kependidikan setiap ada pembaharuan pedoman.
    ','Rektor melalui wakil rektor bidang keuangan harus menyediakan biaya kesejahteraan dosen dan tenaga kependidikan yang cukup setiap bulan kepada dosen dan tenaga kependidikan sesuai dengan mempertimbangkan kondisi keuangan dan peraturan pemerintah yang berlaku.
    ','Rektor melalui wakil rektor bidang keuangan harus menyediakan kecukupan biaya kesejahteraan dosen dan tenaga kependidikan dosen dan tenaga kependidikan setiap periode akademik.
    ','Rektor melalui wakil rektor bidang keuangan, Dekan dan bidang keuangan harus mengevaluasi kesejahteraan dosen dan tenaga kependidikan setiap akhir tahun anggaran untuk memastikan bahwa standar telah terpenuhi atau telah ditaati.
     '       ]];

        $createdStandards = [];
        try {
            $role = ['admin', 'auditee', 'auditor'];
            $name = ['Hendri Prastyo, S.Pd., M.PMat','Admin Universitas Advent Indonesia','Audite Universitas Advent Indonesia'];
            $username = ['auditor','admin','audit1'];
            $password = ['123','123','123'];


            // Array fakultas dengan program studi terkait
            $fakultasProdi = [
                'Fakultas Keguruan dan Ilmu Pendidikan' => ['Pendidikan Matematika','Pendidikan Bahasa Inggris'],
                'Fakultas Filsafat' => ['Ilmu Filsafat', 'Magister Ilmu Filsafat'],
                'Fakultas Ekonomi' => ['Akuntansi', 'Bisnis Digital', 'Manajemen', 'Magister Manajemen'],
                'Fakultas Matematika dan Ilmu Pengetahuan Alam' => ['Biologi', 'Farmasi'],
                'Fakultas Ilmu Keperawatan' => ['Ilmu Keperawatan', 'Keperawatan Umum', 'Profesi NERS', 'Magister Keperawatan'],
                'Fakultas Teknologi Informasi' => [ 'Teknik Informatika','Sistem Informasi']
            ];

            // Menyimpan data ke database
            foreach ($fakultasProdi as $fakultas => $prodis) {
                foreach ($prodis as $prodi) {
                    $existingFakultas = Fakultas::where('fakultas', $fakultas)->where('prodi', $prodi)->first();
                    if ($existingFakultas) {
                        return response()->json(['error' => 'Fakultas already exists'], 400);
                    }
                    Fakultas::create([
                        'fakultas' => $fakultas,
                        'prodi' => $prodi
                    ]);
                }
            }
            $idfakultas = 1;
            $roleLPM = null;
            $roleAdmin = null;
            $roleUser = null;

            // Create Roles
            for ($i = 0; $i < count($role); $i++) {
                $existingRole = Roles::where('jenis', $role[$i])->first();
                if ($existingRole) {
                    return response()->json(['error' => 'Role ' . $role[$i] . ' already exists'], 400);
                }
                $roles = Roles::create([
                    'jenis' => $role[$i],
                ]);
                if ($role[$i] == 'auditee'){
                    $roleUser = $roles->id;
                }
                if ($role[$i] == 'auditor') {
                    $roleLPM = $roles->id;
                } if ($role[$i] == 'admin') {
                    $roleAdmin = $roles->id;
                }
            }

            $idLPM = null;
            // Create User
            for ($i = 0; $i < count($name); $i++) {
                $existingUser = Users::where('username', $username[$i])->first();
                if ($existingUser) {
                    return response()->json(['error' => 'User ' . $username[$i] . ' already exists'], 400);
                }
                $id_roles = null;
                if ($username[$i] == 'auditor') {
                    $id_roles = $roleLPM;
                } elseif ($username[$i] == 'admin') {
                    $id_roles = $roleAdmin;
                }elseif ($username[$i] == 'audit1') {
                    $id_roles = $roleUser;
                }
                if($username[$i]=='audit1'){
                    Users::create([
                        'username' => $username[$i],
                        'name' => $name[$i],
                        'password' => Hash::make($password[$i]),
                        'id_roles' => $id_roles,
                        'id_fakultas' => $idfakultas,
                    ]);
                }else{
                    $User = Users::create([
                        'username' => $username[$i],
                        'name' => $name[$i],
                        'password' => Hash::make($password[$i]),
                        'id_roles' => $id_roles,
                        'id_fakultas' => null,
                    ]);
                    if ($id_roles == $roleLPM) {
                        $idLPM = $User->id ;
                    }
                }
            }

            // Konversi array menjadi format JSON
            $idLPM = is_array($idLPM) ? json_encode($idLPM) : json_encode([$idLPM]);

            // Pastikan konversi berhasil
            if ($idLPM === false) {
                return response()->json(['error' => 'Gagal mengonversi array ke JSON'], 500);
            }
            // Buat Tipe Audit
            $TipeAudit = TipeAudits::create([
                'tipe_audit' => 'Audit Mutu Internal (AMI)',
                'id_fakultas'=>$idfakultas,
                'periode' => '2022/2023',
                'semester' => 'Ganjil',
                'idLPM' => $idLPM,
            ]);

            $createdStandards = [];

            // Create Standars and PerStandars
            for ($i = 0; $i < count($Standar); $i++) {
                $standar = Standars::create([
                    'jenis_standar' => $Standar[$i],
                ]);

                for ($j = 0; $j < count($Pernyataan[$i]); $j++) {
                    PerStandars::create([
                        'id_standars' => $standar->id,
                        'id_tipe_audits' => $TipeAudit->id,
                        'pernyataan_standar' => $Pernyataan[$i][$j],
                        'poin' => '5',
                    ]);
                }
                $createdStandards[] = $standar;
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create data: ' . $e->getMessage()], 500);
        }
        // Kembalikan response sukses setelah loop selesai
        return response()->json($createdStandards, 201);
    }
}
