<template v-if="authStore.role === 'auditee'">
    <!-- Loading Page Start -->
    <div v-if="load" class="bg-gray w-full h-screen skeleton">
        <h1 class="text-[30px] text-center">Loading</h1>
    </div>
    <!-- Loading Page End -->

    <!-- Main Content Start -->
    <div v-else class="relative bg-gray-200 min-h-screen p-4 flex flex-col gap-4 overflow-hidden">
        <!-- Banner Start -->
        <div class="bg-white rounded-xl w-full py-16 px-10 flex flex-col gap-4 shadow-md">
            <p class="text-3xl">Welcome back, <span class="font-semibold">{{ authStore.name }}</span></p>
    <!--            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>-->
    <!--            <p>Mauris fermentum tortor a diam eleifend scelerisque.</p>-->
        </div>
        <!-- Banner End -->

        <!-- Banner Start -->
        <div v-if="!isDataAvailable" class="bg-white rounded-xl w-full p-4 text-center font-semibold text-red-500">
            <p>Tidak ada pernyataan atau standar yang tersedia untuk dikerjakan saat ini.</p>
        </div>
        <!-- Banner End -->

        <!-- Content Start -->
        <div v-if="isDataAvailable" class="grid md:grid-cols-4 gap-4 grid-cols-1">
            <!-- Standar Progress -->
            <div class="bg-white rounded-xl shadow-md divide-y-[1.5px] col-span-2 duration-[2s] ease-in-out" id="auditor">
                <!-- Standar Progress Header Start -->
                <div class="p-4 h-[70px] flex items-center justify-between">
                    <p class="text-lg font-semibold">Standar Progress</p>
                    <select class="bg-sky-500 py-2 px-4 rounded-lg text-white transition-all duration-300 ease-in-out hover:bg-skCy-700 hover:shadow-lg truncate max-w-[50%] text-[15px] capitalize" v-model="form.tipe" @change="myData(form.tipe)">
                        <option disabled class="bg-[#e0dded] py-2">Choose an option</option>
                        <option :value="tipe.id" v-for="tipe in Tipe" :key="tipe.id" class="capitalize">
                            <div class="w-1/2">{{ tipe.tipe_audit }} - {{tipe.fakultas.prodi}} - {{ tipe.periode }} - {{ tipe.semester }}</div>
                        </option>
                    </select>
                </div>
                <!-- Standar Progress Header End -->

                <!-- Standar Progress Content Start -->
                <div class="p-4 h-[380px] overflow-auto no-scrollbar">
                    <ul class="flex flex-col gap-3 w-auto">
                        <li class="flex flex-col gap-1 cursor-pointer p-2 rounded hover:bg-neutral-200 transition duration-150 ease-out hover:ease-in" v-for="(user, index) in users" :key="index" @click="detail(user.id)">
                            <template v-if="users.length > 0">
                                <p>{{ user.name }}</p>
                                <div class="flex items-center justify-between">
                                    <div class="w-1/2 rounded-full bg-gray-400">
                                        <div class="h-3 rounded-full bg-blue-500 transition-width duration-1500 ease-in-out" :style="{ width: user.persentase + '%' }"></div>
                                    </div>
                                    <div class="bg-blue-500 text-white py-1 px-2 rounded text-xs">{{ user.persentase }}%</div>
                                </div>
                            </template>
                            <template v-else>Tidak Ada Standar Dalam Tipe ini</template>
                        </li>
                    </ul>
                </div>
                <!-- Standar Progress Content End -->
            </div>

            <!-- Audit Progress -->
            <div class="bg-white rounded-xl shadow-md divide-y-[1.5px] col-span-2" id="progress">
                <!-- Audit Progress Header Start -->
                <div class="p-4 h-[70px] flex items-center justify-between">
                    <p class="text-lg font-semibold">Audite Progress</p>
                </div>
                <!-- Audit Progress Header End -->

                <!-- Audit Progress Content Start -->
                <div class="p-10 flex flex-col items-center gap-3">
                    <div class="relative flex items-center justify-center hidden md:block">
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
        <div v-if="isDataAvailable" class="bg-white rounded-xl gap-4 shadow-md divide-y-[1.5px] hidden" id="tabel">
            <!-- Standar Header Start -->
            <div class="p-4 h-[70px] flex items-center justify-between">
                <p class="text-lg font-semibold">{{ authStore.name }}</p>
                <div class="flex items-center justify-between">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="cursor-pointer" viewBox="0 0 16 16" @click="close" >
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                    </svg>
                </div>
            </div>
            <!-- Standar Header End -->

            <!-- Table Start -->
            <div class="overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="overflow-auto h-[380px] rounded">
                        <table class="min-w-full dark:divide-neutral-700 relative">
                            <thead>
                                <tr>
                                    <th scope="col" class="sticky top-0 px-6 py-3 text-start text-gray-500 bg-gray-200 w-[60%]">Pernyataan</th>
                                    <th scope="col" class="sticky top-0 px-6 py-3 text-start text-gray-500 bg-gray-200 w-[10%]">Dokumen Pendukung</th>
                                    <th scope="col" class="sticky top-0 px-6 py-3 text-center text-gray-500 bg-gray-200 w-[10%]">Nilai Diri</th>
                                    <th scope="col" class="sticky top-0 px-6 py-3 text-center text-gray-500 bg-gray-200 w-[10%]">Nilai Maksimum</th>
                                    <th scope="col" class="sticky top-0 px-6 py-3 text-center text-gray-500 bg-gray-200 w-[10%]">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100 border-y" v-for="detail in details" :key="detail">
                                    <td class="px-6 py-4 text-start text-gray-800 w-[60%]">
                                        <span>{{ detail.per_standars.pernyataan_standar }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-start text-gray-800 w-[10%]">
                                        <div v-for="(file,index) in files(detail.dok_pendukung)" :key="index" style="cursor: pointer; text-decoration: underline blue 2px; text-align: left" v-if="detail.dok_pendukung != null">
                                            <span @click="open(file)" v-if="file.type == 'pdf' || file.type == 'jpg' || file.type == 'jpeg' || file.type == 'png' || file.type == 'svg'">{{ index + 1 }}.{{ file.name }}</span>
                                            <span @click="download(file)" v-else>{{index + 1 }}.{{ file.name }}</span>
                                        </div>
                                        <div v-else class="text-red-500">Belum ada file</div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-800 w-[10%]">{{ detail.nilai_diri }}</td>
                                    <td class="px-6 py-4 text-center text-gray-800 w-[10%]">{{ detail.per_standars.poin }}</td>
                                    <td class="px-6 py-4 text-center text-white w-[10%]">
                                        <span v-if="detail.dok_pendukung!=null" class="bg-green-500 px-4 py-2 rounded">Done</span>
                                        <span v-else class="bg-red-500 px-4 py-2 rounded">OnProgress</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table End -->
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
// Import Library
import { ref, onMounted } from "vue";
import axios from "axios";
import { useAuthStore } from "@/router/auth.js";

// Variable Declaration
const form = ref({
    tipe: 0,
    standar:"ALL"
});
const authStore = useAuthStore();
const Audit = ref([]);
const Tipe = ref([]);
const datas = ref([]);
const currentProgress = ref({percent: 0})
const circumference = 2 * Math.PI * 120
const circumferences = 2 * Math.PI * 80
const users = ref([]);
const details = ref([]);
const standars = ref([]);
const Standars = ref([]);
const load = ref(true);
const currentIndex = ref(0);
const pdfUrl = ref();
document.title = `Dashboard | ${authStore.name}`;
const isDataAvailable = ref(false);

// Fetch data menggunakan onMounted
onMounted(async () => {
    try {
        const [resAudit, resTipe, resStandar] = await Promise.all([
            axios.get(`${authStore.url}/audits/${authStore.uid}`,  authStore.header),
            axios.get(`${authStore.url}/MyTipeAudits/${authStore.uid}`,  authStore.header),
            axios.get(`${authStore.url}/standars`,  authStore.header)
        ]);

        Audit.value = resAudit.data;
        Tipe.value = resTipe.data;
        Standars.value = resStandar.data;

        if (Tipe.value.length > 0) {
            form.value.tipe = Tipe.value[0].id;
            myData(form.value.tipe);
        }

        load.value = false;
        isDataAvailable.value = true;
    } catch (error) {
        load.value = false;
        console.error("Failed to fetch Audit, Tipe, or Standar data:", error);
    }
});



// myData Method
const myData = (param) => {
    datas.value = [];

    Audit.value.forEach((res) => {
        if(res.tipe_audits.id == param){
            datas.value.push(res)
        }
        else{
            users.value = null;
            currentProgress.value.percent = 0;
        }
    });

    unikStandars();
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

// Detail Method
const detail = (id) => {
    details.value = [];
    standars.value =[];

    // Find matching audit data and add to details
    for (let i = 0; i < datas.value.length; i++) {
        if (datas.value[i].per_standars.id_standars == id) {
            details.value.push(datas.value[i]);
        }
    }

    document.getElementById("tabel").classList.add("block");
    document.getElementById("tabel").classList.remove("hidden");
};

// Close Method
const close = () => {
    document.getElementById("tabel").classList.remove("block");
    document.getElementById("tabel").classList.add("hidden");
    document.getElementById("progress").classList.remove("hidden");
    document.getElementById("auditor").classList.add("col-span-2");
}

// Unique Stadar Method
const unikStandars = () => {
    const StandarArray = [];
    let totalPersentase = 0;

    datas.value.forEach((res) => {
        const StandarId = res.per_standars.id_standars;
        let nameStandar = null;

        // Cari nameStandar berdasarkan StandarId
        const standar = Standars.value.find(s => s.id == StandarId);
        if (standar) {
            nameStandar = standar.jenis_standar;
        }

        const dokPendukung = res.dok_pendukung;
        const NilaiDiri = res.nilai_diri;

        // Cari Standar di StandarArray
        let existingStandar = StandarArray.find(item => item.id == StandarId);

        // Jika StandarId belum ada di StandarArray, tambahkan dengan poin awal 0
        if (!existingStandar) {
            existingStandar = { id: StandarId, name: nameStandar, poin: 0, current: 0 };
            StandarArray.push(existingStandar);
        }

        // Hitung jumlah dok_pendukung yang tidak null dan tambahkan ke poin
        if (dokPendukung != null ) {
            existingStandar.poin += 1;
        }

        existingStandar.current += 1;
    });

    // Menghitung persentase poin untuk setiap standar
    StandarArray.forEach((standar) => {
        standar.persentase = Math.round((standar.poin / standar.current) * 100);
        totalPersentase += standar.persentase;
    });

    // Menghitung rata-rata persentase
    const averagePersentase = StandarArray.length > 0 ? totalPersentase / StandarArray.length : 0;

    // Set currentProgress
    currentProgress.value.percent = averagePersentase;

    // Simpan StandarArray di users.value
    users.value = StandarArray;
};

// Open File Method
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

// Exit Method
const keluar = () => {
    let carousel = document.getElementById('carousel');
    carousel.classList.add('hidden');
    carousel.classList.remove('block')
}
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
