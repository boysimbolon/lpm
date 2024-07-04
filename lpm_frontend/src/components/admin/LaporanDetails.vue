<template v-if="authStore.role === 'admin'">
    <div class="relative bg-gray-200 min-h-screen p-4 flex flex-col gap-4 w-full overflow-hidden">
        <div class="w-full bg-white rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Detail Laporan - Pernyataan</h1>

                <!-- Visualization Button -->
<!--                <button @click="Visual" class="flex items-center gap-2 bg-blue-500 hover:bg-blue-700 shadow-md text-white py-2 px-4 rounded">-->
<!--                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-graph-up-arrow" viewBox="0 0 16 16">-->
<!--                        <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5"/>-->
<!--                    </svg>-->
<!--                    Visualisasi-->
<!--                </button>-->

                <!-- Back Button -->
                <button @click="goBack(authStore.idParams.idStandar, authStore.idParams.idParams.tipeAudit, authStore.idParams.fakultas, authStore.idParams.prodi, authStore.idParams.idParams.tahun, authStore.idParams.idParams.semester)" class="flex items-center gap-2 bg-neutral-400 hover:bg-neutral-500 shadow-md text-white py-2 px-4 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                    Back
                </button>
            </div>
            <!-- Header End -->

            <!-- Table Start -->
            <div class="p-4 overflow-auto flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-lg font-semibold capitalize">{{ authStore.idParams.idParams.tipeAudit }} - {{ authStore.idParams.idParams.tahun }} - Semester {{ authStore.idParams.idParams.semester }}</h1>
                </div>
                <h1 class="text-lg font-medium">{{ authStore.idParams.jstandar }}</h1>
                <table class="table-auto text-left w-full">
                    <thead>
                        <tr>
                            <th class="p-4 w-[5%] text-center">No.</th>
                            <th class="p-4 w-[10%]">Auditee</th>
                            <th class="p-4 w-[30%]">Pernyataan Standar</th>
                            <th class="p-4 w-[10%]">Dokumen Pendukung</th>
                            <th class="p-4 w-[5%] text-center text-nowrap">Nilai Diri<br>Diri/Max</th>
                            <th class="p-4 w-[5%] text-center text-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="Audits && Audits.length > 0">
                            <tr v-for="(audit, auditIndex) in paginatedAudits" :key="auditIndex" class="border-y">
                                <td class="p-4 w-[5%] text-center">{{ auditIndex + 1 + (currentPage - 1) * itemsPerPage }}</td>
                                <td class="p-4 w-[10%]">{{ audit.users.name }}</td>
                                <td class="p-4 w-[30%]">{{ audit.per_standars.pernyataan_standar }}</td>
                                <td class="p-4 w-[10%]">
                                    <div v-for="(file,index) in files(audit.dok_pendukung)" :key="index" style="cursor: pointer; text-decoration: underline blue 2px; text-align: left" v-if="audit.dok_pendukung != null">
                                        <span @click="open(file)" v-if="file.type == 'pdf' || file.type == 'jpg' || file.type == 'jpeg' || file.type == 'png' || file.type == 'svg'">{{ index + 1 }}.{{ file.name }}</span>
                                        <span @click="download(file)" v-else>{{ index + 1 }}.{{ file.name }}</span>
                                    </div>
                                    <div v-else class="text-red-400">Belum ada file</div>
                                </td>
                                <td class="p-4 w-[5%] text-center">{{ audit.nilai_diri }}/{{ audit.per_standars.poin }}</td>
                                <td v-if="audit.status === 'Tidak Memenuhi'" class="p-4 w-[5%] text-center text-white">
                                    <div class="p-2 rounded bg-red-500">
                                        <span>{{ audit.status }}</span>
                                    </div>
                                </td>
                                <td v-else-if="audit.status === 'Terpenuhi'" class="p-4 w-[5%] text-center text-white">
                                    <div class="p-2 rounded bg-green-500">
                                        <span>{{ audit.status }}</span>
                                    </div>
                                </td>
                                <td v-else-if="audit.status === 'Melampaui'" class="p-4 w-[5%] text-center text-white">
                                    <div class="p-2 rounded bg-blue-500">
                                        <span>{{ audit.status }}</span>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td colspan="7" class="p-4 text-center">Belum Ada Audit yang berhasil di kunci</td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination flex justify-center items-center gap-2">
                    <button @click="previousPage" :disabled="currentPage === 1" class="py-2 px-4 bg-gray-300 rounded">Previous</button>
                    <button v-for="page in totalPages" :key="page" @click="goToPage(page)" :class="{ 'bg-blue-500 text-white': page === currentPage }" class="py-2 px-4 bg-gray-300 rounded">{{ page }}</button>
                    <button @click="nextPage" :disabled="currentPage === totalPages" class="py-2 px-4 bg-gray-300 rounded">Next</button>
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
</template>

<script setup>
// Import Library
import { ref, computed, onMounted } from 'vue';
import { useAuthStore } from '@/router/auth.js';
import { useRouter } from "vue-router";
import axios from 'axios';

// Variable Declaration
const Audits = ref([]);
const authStore = useAuthStore();
const router = useRouter();
const currentPage = ref(1);
const itemsPerPage = ref(10);
const pdfUrl = ref();
const filteredAudits = ref([]);
document.title = `Detail Laporan - Pernyataan | ${authStore.name}`;

// Total Pages Method
const totalPages = computed(() => {
    return Math.ceil(filteredAudits.value.length / itemsPerPage.value);
});

// Pagination Method
const paginatedAudits = computed(() => {

    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return Audits.value.slice(start, end); // Mengembalikan hasil pagination dari filteredAudits

});

// Fetching Data Method
const fetchData = async () => {
    try {
        // Mengambil data audits dan standars secara bersamaan
        const [resAudits] = await Promise.all([
            axios.get(`${authStore.url}/DetailAudit/${authStore.idParams.idParams.idtipe}/${authStore.idParams.idStandar}`,authStore.header),
        ]);
        // Memastikan resAudits.data adalah array
        if (Array.isArray(resAudits.data)) {
            // Mengonversi nilai poin dan nilai_diri menjadi integer dan menambahkan status
            Audits.value = resAudits.data.map(res => {
                const poin = parseInt(res.per_standars.poin, 10);
                const nilaiDiri = parseInt(res.nilai_diri, 10);

                let dif = ''; // Variabel untuk menyimpan status

                if (poin <= nilaiDiri) {
                    if (nilaiDiri == poin) {
                        dif = 'Terpenuhi'; // Jika nilai_diri sama dengan poin
                    } else {
                        dif = 'Melampaui'; // Jika nilai_diri lebih dari poin
                    }
                } else {
                    dif = 'Tidak Memenuhi'; // Jika nilai_diri kurang dari atau sama dengan poin
                }

                return {
                    ...res,
                    status: dif
                };
            });

        } else {
            console.error('Data returned is not an array:', resAudits.data);
        }
    } catch (error) {
        console.error('Error fetching data:', error);
    }
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

// OnMounted Method
onMounted(() => {
    fetchData();
});

// Go To Method
const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

// Next Page Method
const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

// Previous Page Method
const previousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

// Back Method
const goBack = (idtipe, tipe, fakultas, prodi, tahun, semester) => {
    const data = {
        idtipe: idtipe,
        tipeAudit: tipe,
        fakultas: fakultas,
        prodi: prodi,
        tahun: tahun,
        semester: semester
    };
    authStore.scope(data);
    router.push({ name: 'DetailStandarAsAdmin' });
};

// Visual Method
const Visual = () => {
    router.push({ name: 'ChartComponent' });
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
.pagination button.bg-blue-500 {
    background-color: #4299e1;
    color: white;
}

.pagination button:hover {
    background-color: #2b6cb0;
    color: white;
}
</style>
