<template v-if="authStore.role === 'admin'">
    <!-- Loading Page Start -->
    <div v-if="load" class="bg-gray w-full h-screen skeleton">
        <h1 class="text-[30px] text-center">Loading</h1>
    </div>
    <!-- Loading Page Start -->

    <!-- Main Content Start -->
    <div v-else class="relative bg-gray-200 min-h-screen p-4 flex flex-col gap-4 w-full overflow-hidden">
        <!-- Banner Start -->
        <div class="bg-white rounded-xl w-full py-16 px-10 flex flex-col gap-4 shadow-md">
            <p class="text-3xl">Welcome back, <span class="font-semibold">{{ authStore.name }}</span></p>
<!--            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>-->
<!--            <p>Mauris fermentum tortor a diam eleifend scelerisque.</p>-->
        </div>
        <!-- Banner End -->

        <!-- Content Start -->
        <div class="grid md:grid-cols-4 gap-4 grid-cols-1">
            <!-- Auditor Progress -->
            <div class="bg-white rounded-xl shadow-md divide-y-[1.5px] col-span-2 duration-[2s] ease-in-out" id="auditor">
                <!-- Auditor Progress Header Start -->
                <div class="p-4 h-[70px] flex items-center justify-between">
                    <p class="text-lg font-semibold">Auditee Progress</p>
                    <select class="bg-sky-500 py-2 px-4 rounded-lg text-white transition-all duration-300 ease-in-out hover:bg-sky-700 hover:shadow-lg truncate max-w-[50%] text-[15px] capitalize" v-model="form.tipe" @change="myData(form.tipe)">
                        <option disabled class="bg-[#e0dded] py-2">Choose an option</option>
                        <option :value="tipe.id" v-for="tipe in Tipe" :key="tipe.id" class="capitalize">
                            <span class="w-1/2">{{ tipe.tipe_audit }} - {{tipe.fakultas.prodi}} - {{ tipe.periode }} - {{ tipe.semester }}</span>
                        </option>
                    </select>
                </div>
                <!-- Auditor Progress Header End -->

                <!-- Auditor Progress Content Start -->
                <div class="p-4 h-[380px] overflow-auto no-scrollbar">
                    <ul class="flex flex-col gap-3 w-auto">
                        <li class="flex flex-col gap-1 cursor-pointer p-2 rounded hover:bg-neutral-200 transition duration-150 ease-out hover:ease-in" v-for="(user, index) in users" :key="index" @click="detail(user.id)">
                            <template v-if="users && users.length > 0">
                                <p>{{ user.name }}</p>
                                <div class="flex items-center justify-between">
                                    <div class="w-1/2 rounded-full bg-gray-400">
                                        <div class="h-3 rounded-full bg-blue-500 transition-width duration-1500 ease-in-out" :style="{ width: user.persentase + '%' }"></div>
                                    </div>
                                    <div class="bg-blue-500 text-white py-1 px-2 rounded text-xs">{{ user.persentase }}%</div>
                                </div>
                            </template>
                            <template v-else>Tidak Ada Auditor Dalam Tipe ini</template>
                        </li>
                    </ul>
                </div>
                <!-- Auditor Progress Content End -->
            </div>

            <!-- Audit Progress -->
            <div class="bg-white rounded-xl shadow-md divide-y-[1.5px] col-span-2" id="progress">
                <!-- Audit Progress Header Start -->
                <div class="p-4 h-[70px] flex items-center justify-between">
                    <p class="text-lg font-semibold">Audit Progress</p>
                </div>
                <!-- Audit Progress Header End -->

                <!-- Audit Progress Content Start -->
                <div class="p-10 flex flex-col items-center gap-3">
                    <div class="relative flex items-center justify-center hidden md:block text-start">
                        <svg class="transform -rotate-90 w-72 h-72">
                            <circle cx="145" cy="145" r="120" stroke="currentColor" stroke-width="20" fill="transparent" class="text-gray-700"/>
                            <circle cx="145" cy="145" r="120" stroke="currentColor" stroke-width="20" stroke-linecap="round" fill="transparent" :stroke-dasharray="circumference" :stroke-dashoffset="circumference - currentProgress.percent / 100 * circumference" class="text-blue-500 transition-color duration-[2s] ease-in-out"/>
                        </svg>
                        <div class="absolute inset-0 text-5xl flex items-center justify-center">
                            <span>{{ currentProgress.percent.toFixed(1) }}%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-center block md:hidden">
                        <svg class="transform -rotate-90 w-72 h-72">
                            <circle cx="145" cy="145" r="80" stroke="currentColor" stroke-width="20" fill="transparent" class="text-gray-700"/>
                            <circle cx="145" cy="145" r="80" stroke="currentColor" stroke-width="20" stroke-linecap="round" fill="transparent" :stroke-dasharray="circumferences" :stroke-dashoffset="circumferences - currentProgress.percent / 100 * circumferences" class="text-blue-500 transition-color duration-[2s] ease-in-out"/>
                        </svg>
                        <span class="absolute text-5xl">{{ currentProgress.percent.toFixed(1) }}%</span>
                    </div>
                    <div class="flex items-center justify-center gap-10">
                        <p class="flex items-center gap-2">
                            <svg class="transform -rotate-90 w-2 h-2">
                                <circle cx="4" cy="4" r="4" fill="#3b82f6"/>
                            </svg>
                            Done
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="transform -rotate-90 w-2 h-2">
                                <circle cx="4" cy="4" r="4" fill="#374151"/>
                            </svg>
                            Progress
                        </p>
                    </div>
                </div>
                <!-- Audit Progress Content End -->
            </div>
        </div>
        <!-- Content End -->

        <!-- Standar -->
        <div class="bg-white rounded-xl gap-4 shadow-md divide-y-[1.5px] hidden" id="tabel">
            <!-- Standar Header Start -->
            <div class="p-4 flex items-center justify-between">
                <p class="text-lg font-semibold">{{ auditor }}</p>
                <div class="flex items-center justify-between">
                    <select
                        class="bg-sky-500 py-2 px-4 rounded-lg text-white transition-all duration-300 ease-in-out hover:bg-sky-700 hover:shadow-lg truncate w-3/4"
                        v-model="form.standar" @change="detail1()">
                        <option disabled class="bg-[#e0dded] py-2">Choose an option</option>
                        <option value="ALL" class="text-sm">All Standar</option>
                        <option :value="standar.id" v-for="standar in standars" :key="standar.id" class="text-sm">{{ standar.jenis_standar }}</option>
                    </select>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="cursor-pointer" viewBox="0 0 16 16" @click="close">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                    </svg>
                </div>
            </div>
            <!-- Standar Header End -->

            <!-- Table Start -->
            <div class="overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="overflow-auto h-[380px]">
                        <table class="min-w-full relative">
                            <thead>
                                <tr class="sticky top-0 bg-gray-200">
                                    <th scope="col" class="sticky top-0 px-6 py-3 text-start text-gray-500 w-[60%]">Pernyataan</th>
                                    <th scope="col" class="sticky top-0 px-6 py-3 text-start text-gray-500 w-[10%]">Dokumen Pendukung</th>
                                    <th scope="col" class="sticky top-0 px-6 py-3 text-center text-gray-500 w-[10%]">Nilai Diri</th>
                                    <th scope="col" class="sticky top-0 px-6 py-3 text-center text-gray-500 w-[10%]">Nilai Maksimum</th>
                                    <th scope="col" class="sticky top-0 px-6 py-3 text-center text-gray-500 w-[10%]">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100" v-for="detail in details" :key="detail">
                                    <td class="px-6 py-4 text-start text-gray-800 w-[60%]">
                                       <span >{{ detail.per_standars.pernyataan_standar }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-start text-gray-800 w-[10%]">
                                            <div v-for="(file,index) in files(detail.dok_pendukung)" :key="index" style="cursor: pointer; text-decoration: underline blue 2px; text-align: left" v-if="detail.dok_pendukung != null">
                                                <span @click="open(file)" v-if="file.type == 'pdf' || file.type == 'jpg' || file.type == 'jpeg' || file.type == 'png' || file.type == 'svg'">{{ index + 1 }}.{{ file.name }}</span>
                                                <span @click="download(file)" v-else>{{ index + 1 }}.{{ file.name }}</span>
                                            </div>
                                            <div v-else class="text-red-400">Belum ada file</div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-800 w-[10%]">{{ detail.nilai_diri }}</td>
                                    <td class="px-6 py-4 text-center text-gray-800 w-[10%]">{{ detail.per_standars.poin }}</td>
                                    <td class="px-6 py-4 text-center w-[10%] text-white">
                                        <span v-if="detail.dok_pendukung != null && detail.nilai_diri != 0" class="bg-green-500 px-4 py-2 rounded">Done</span>
                                        <span v-else class="bg-red-500 px-4 py-2 rounded">OnProgress</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Unlock Button -->
                    <button class="py-2 px-4 bg-blue-500 w-fit rounded my-3 hover:cursor-pointer hover:bg-white hover:border-2" @click="Open(details)" v-if="form.standar != 'ALL' && status">Buka Kunci</button>
                </div>
            </div>
        </div>

        <!-- Carousel Start -->
        <div class="absolute hidden bg-gray-200/75 min-w-full min-h-full pr-10" id="carousel">
            <div class="fixed top-0 flex flex-col items-center justify-center h-full max-w-full pr-4">
                <div v-for="(url, index) in pdfUrl" :key="index" class="pt-4 w-full h-full">
                    <div class="flex flex-col gap-4">
                        <!-- Carousel Header Start -->
                        <div class="flex w-full items-center">
                            <h1 class="text-2xl font-bold mx-auto">{{ url.name }}</h1>
                            <!-- Close Button -->
                            <button @click="keluar" class="py-2 px-4 w-fit rounded bg-red-500 text-white hover:bg-red-700 cursor-pointer shadow-md">Keluar</button>
                        </div>
                        <!-- Carousel Header End -->
                    </div>

                    <!-- Preview File Start -->
                    <div class="flex justify-center mt-4">
                        <iframe v-if="url.type == 'pdf'" :src="url.file" frameborder="0" class="border border-black w-screen max-w-full" style="height: 1000px"></iframe> <!-- Adjusted height for better fit -->
                        <img v-else :src="url.file"  class="border border-black w-screen" style="height: 1000px">
                    </div>
                    <!-- Preview File End -->
                </div>
            </div>
        </div>
        <!-- Carousel End -->
    </div>
    <!-- Main Content End -->
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useAuthStore } from "@/router/auth.js";
import router from "@/router/index.js";

// Variable Declaration
const form = ref({
    tipe: 0,
    standar: "ALL"
});
const authStore = useAuthStore();
const auditor = ref(null);
const Router = router;
const Audit = ref([]);
const Tipe = ref([]);
const datas = ref([]);
const currentProgress = ref({percent: 0})
const circumference = 2 * Math.PI * 120
const circumferences = 2 * Math.PI * 80
const users = ref([]);
const details = ref([]);
const status = ref(true);
const standars = ref([]);
const Standars = ref([]);
const load = ref(true);
const pdfUrl = ref();

// Fetch data menggunakan onMounted
onMounted(async () => {
    if(authStore.role != 'admin'){
        alert("Password dan Username Tidak Tepat");
        router.push({ name: "login" });
    }
    try {
        const [resAudit, resTipe, resStandar] = await Promise.all([
            axios.get(`${authStore.url}/audits`, authStore.header),
            axios.get(`${authStore.url}/tipeAudits`, authStore.header),
            axios.get(`${authStore.url}/standars`, authStore.header)
        ]);

        Audit.value = resAudit.data;
        Tipe.value = resTipe.data;
        Standars.value = resStandar.data;

        if(Tipe.value.length > 0){
            form.value.tipe = Tipe.value[0].id
            myData(form.value.tipe);
        }

        load.value = false;
    } catch (error) {
        console.error("Failed to fetch Audit or Tipe data:", error);
    }
});

document.title = `Dashboard | ${authStore.name}`;

// My Data Method
const myData = (param) => {
    datas.value = [];
    for (let i = 0; i < Audit.value.length; i++) {
        if (Audit.value[i] !== null) {
            if (Audit.value[i].id_tipe_audits == param) {
                datas.value.push(Audit.value[i]);
            }
        }
    }
    unikUsers();
};

// Open Method
const open = (id) => {
    let carousel = document.getElementById('carousel');
    carousel.classList.add('hidden');
    carousel.classList.remove('block')
    pdfUrl.value = [];
    pdfUrl.value.push({file:`${authStore.fileUrl}/${id.file}`,name:id.name, type:id.type});
    console.log(pdfUrl.value);
    carousel.classList.add('block');
    carousel.classList.remove('hidden')
};

// Files Method
const files = (dokumen) => {
    if (dokumen != null) {
        const Dokumens = JSON.parse(dokumen);
        const modifiedDokumens = Dokumens.map((item) => {
            const splitArray = item.split('/');
            const fullName = splitArray[splitArray.length - 1];
            const nameParts = fullName.split('.');
            const name = nameParts.slice(0, -1).join('.'); // Menggabungkan kembali nama tanpa ekstensi
            const type = nameParts[nameParts.length - 1];
            return {
                file: item,
                name: name,
                type: type
            };
        });
        return modifiedDokumens;
    }
    return []; // Mengembalikan array kosong jika dokumen null
};

// Download Method
const download = async (dokumen) => {
    if (confirm("File ini tidak dapat di preview, anda akan mendownload file tersebut!\n Ingin tetap mendownload?")) {
        try {
            window.location.href = `${authStore.fileUrl}/${dokumen.file}`;
        } catch (error) {
            console.error('File corrupt', error);
        }
    }
};

// Exit Method
const keluar = () => {
    let carousel = document.getElementById('carousel');
    carousel.classList.add('hidden');
    carousel.classList.remove('block')
}

// Detail Method
const detail = (id) => {
    // Clear details before adding new data
    details.value = [];
    standars.value = [];
    form.value.standar = 'ALL';
    // Find matching audit data and add to details
    for (let i = 0; i < datas.value.length; i++) {
        if (datas.value[i].id_users == id) {
            details.value.push(datas.value[i]);
        }
    }
    const uniqueStandars = new Set();
    for (let i = 0; i < details.value.length; i++) {
        const id_standar = details.value[i].per_standars.id_standars;
        if (!uniqueStandars.has(id_standar)) {
            const foundStandar = Standars.value.find(s => s.id == id_standar);
            if (foundStandar) {
                uniqueStandars.add(id_standar);
                standars.value.push(foundStandar);
            }
        }
    }
    document.getElementById("tabel").classList.add("block");
    document.getElementById("tabel").classList.remove("hidden");
    // Display auditor's name if details are not empty
    if (details.value.length > 0) {
        auditor.value = details.value[0].users.name;
    }
};

// Close Method
const close = () => {
    details.value = [];
    form.value.standar = 'ALL';
    document.getElementById("tabel").classList.remove("block");
    document.getElementById("tabel").classList.add("hidden");
    document.getElementById("progress").classList.remove("hidden");
    document.getElementById("auditor").classList.add("col-span-2");
    let carousel = document.getElementById('carousel');
    carousel.classList.add('hidden');
    carousel.classList.remove('block')
    pdfUrl.value = [];
}

// Detail1 Method
const detail1 = () => {
    details.value = [];
    for (let i = 0; i < datas.value.length; i++) {
        if (datas.value[i].users.name == auditor.value && datas.value[i].id_tipe_audits == form.value.tipe &&
            datas.value[i].per_standars.id_standars == form.value.standar) {
            details.value.push(datas.value[i]);
            if (datas.value[i].progress == '0') {
                status.value = false;
            }
        } else if (form.value.standar == "ALL" && datas.value[i].users.name == auditor.value &&
            datas.value[i].id_tipe_audits == form.value.tipe) {
            details.value.push(datas.value[i]);
        }
    }
    if (details.value.length > 0) {
        auditor.value = details.value[0].users.name;
    }
}

// Unique Users Method
const unikUsers = () => {
    const UsersMap = new Map();
    let totalPersentase = 0;

    datas.value.forEach((res) => {
        const userId = res.id_users;
        const userName = res.users.name;
        const dokPendukung = res.dok_pendukung;
        const tipe = res.id_tipe_audits;
        // Kunci baru untuk map dengan kombinasi userId dan tipe audit
        const userKey = `${userId}-${tipe}`;
        // Jika pengguna belum ada di Map untuk tipe audit tersebut, tambahkan dengan poin awal 0
        if (!UsersMap.has(userKey)) {
            UsersMap.set(userKey, {id: userId, name: userName, poin: 0, tipe: tipe, current: 0});
        }
        // Hitung jumlah dok_pendukung yang tidak null dan tambahkan ke poin
        if (dokPendukung != null && res.nilai_diri != '0') {
            UsersMap.get(userKey).poin += 1;
        }
        UsersMap.get(userKey).current += 1;
    });

    // Menghitung persentase poin untuk setiap pengguna
    UsersMap.forEach((user) => {
        user.persentase = Math.round((user.poin / user.current) * 100);
        totalPersentase += user.persentase;
    });

    // Menghitung rata-rata persentase
    const averagePersentase = UsersMap.size > 0 ? totalPersentase / UsersMap.size : 0;

    // Set currentProgress
    currentProgress.value.percent = averagePersentase;

    // Mengonversi UsersMap ke array dan menetapkan ke users.value
    users.value = Array.from(UsersMap.values());
};

// Open Method
const Open = (data) => {
    let isError = false; // Variabel untuk melacak kesalahan
    let putRequests = []; // Array untuk menyimpan permintaan PUT
    data.forEach((res) => {
        if (res.progress == '0') {
            isError = true;
        }
    });
    if (isError) {
        alert('Standar Ini Belum Dikunci.');
    } else {
        if (confirm('Yakin ingin Membuka kembali Audit ini?')) {
            putRequests = data.map((res) => {
                if (res.progress != '0') {
                    return axios.put(`${authStore.url}/OpenAudit/${res.id_tipe_audits}/${res.id_users}/${res.per_standars.id_standars}`, {
                        progress: 0
                    },authStore.header);
                }
            }).filter(request => request !== undefined); // Menghapus nilai undefined dari array

            // Menjalankan semua permintaan PUT secara paralel
            Promise.all(putRequests)
                .then(responses => {
                    responses.forEach(response => console.log("Berhasil:", response.data));
                    Router.push({name: 'LPM'})
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    }
};
</script>

<style>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.no-scrollbar {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}

/* In your CSS file or <style> section */
.progress-bar {
    transition: width 500ms linear;
}

</style>
