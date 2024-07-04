<template v-if="authStore.role === 'admin'">
    <div class="relative bg-gray-200 min-h-screen p-4 flex flex-col gap-4 w-full">
        <div class="w-full bg-white rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Detail Laporan - Standar</h1>
                <!-- Back Button -->
                <button @click="goBack" class="flex items-center gap-2 bg-neutral-400 hover:bg-neutral-500 shadow-md text-white py-2 px-4 rounded">
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
                    <h1 class="text-lg font-semibold capitalize">{{ authStore.idParams.tipeAudit }} - {{ authStore.idParams.fakultas }} -  {{ authStore.idParams.prodi }}</h1>
                    <h1 class="text-lg font-semibold capitalize">{{ authStore.idParams.tahun }} - Semester {{ authStore.idParams.semester }}</h1>
                </div>
                <table class="table-auto text-left w-full">
                    <thead>
                        <tr>
                            <th class="p-4 w-[5%] text-center">No.</th>
                            <th class="p-4 w-[60%]">Jenis Standar</th>
                            <th class="p-4 w-[15%] text-center">Status</th>
                            <th class="p-4 w-[10%] text-center">Tingkat Capaian</th>
                            <th class="p-4 w-[10%] text-center text-nowrap">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="filteredAudits && filteredAudits.length > 0">
                            <tr v-for="(audit, auditIndex) in paginatedAudits" :key="auditIndex" class="border-y">
                                <td class="p-4 w-[5%] text-center">{{ auditIndex + 1 + (currentPage - 1) * itemsPerPage }}</td>
                                <td class="p-4 w-[60%]">{{ audit.jenisStandar }}</td>
                                <td class="p-4 w-[15%]">
                                    <div class="flex items-center justify-center text-center text-white">
                                        <span v-if="audit.status" class="bg-green-500 py-2 px-4 rounded shadow-md">Terverifikasi</span>
                                        <span v-else class="bg-red-500 py-2 px-4 rounded shadow-md">Tidak Terverifikasi</span>
                                    </div>
                                </td>
                                <td class="p-4 w-[10%] text-center">{{ audit.capaian }}%</td>
                                <td class="p-4 w-[10%] text-center text-white">
                                    <button @click="detail(audit.id,audit.jenisStandar, authStore.idParams.fakultas, authStore.idParams.prodi)" class="bg-blue-500 py-2 px-4 rounded shadow-md hover:bg-blue-700">View</button>
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td colspan="5" class="p-4 text-center">Belum Ada Audit yang berhasil di kunci</td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination flex justify-center items-center gap-2">
                    <button @click="previousPage" :disabled="currentPage === 1" class="py-2 px-4 bg-gray-300 rounded shadow-md">Previous</button>
                    <button v-for="page in totalPages" :key="page" @click="goToPage(page)" :class="{ 'bg-blue-500 text-white': page === currentPage }" class="py-2 px-4 bg-gray-300 rounded shadow-md">{{ page }}</button>
                    <button @click="nextPage" :disabled="currentPage === totalPages" class="py-2 px-4 bg-gray-300 rounded shadow-md">Next</button>
                </div>
            </div>
            <!-- Table End -->
        </div>
    </div>
</template>

<script setup>
// Import Library
import { ref, computed, onMounted } from 'vue';
import { useAuthStore } from '@/router/auth.js';
import { useRouter } from "vue-router";
import axios from 'axios';

// Variable Declaration
const authStore = useAuthStore();
const router = useRouter();
const currentPage = ref(1);
const itemsPerPage = ref(10);
const filteredAudits = ref([]);
document.title = `Detail Laporan - Standar | ${authStore.name}`;

// Total Pages Method
const totalPages = computed(() => {
    return Math.ceil(filteredAudits.value.length / itemsPerPage.value);
});

// Pagination Method
const paginatedAudits = computed(() => {

    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredAudits.value.slice(start, end); // Mengembalikan hasil pagination dari filteredAudits

});

// Fetch Data Method
const fetchData = async () => {
    try {
        const resAudits = await axios.get(`${authStore.url}/ViewStandarsLaporan/${authStore.idParams.idtipe}`,
           authStore.header
        );

        if (Array.isArray(resAudits.data)) {
            const groupedData = {};

            resAudits.data.forEach(item => {
                const idStandars = item.per_standars.id_standars;
                const jenisStandar = item.per_standars.standars.jenis_standar;
                const poin = parseFloat(item.per_standars.poin);
                const nilaiDiri = parseFloat(item.nilai_diri);
                const progress = parseInt(item.progress);

                const groupKey = `${idStandars}_${jenisStandar}`;

                if (!groupedData[groupKey]) {
                    groupedData[groupKey] = {
                        idStandars: idStandars,
                        jenisStandar: jenisStandar,
                        totalPoin: 0,
                        allProgressOne: true,
                        different: 0,
                        capaian: 0
                    };
                }

                groupedData[groupKey].totalPoin += poin;

                if (progress !== 1) { // changed from 'progress != '1'' to 'progress !== 1'
                    groupedData[groupKey].allProgressOne = false;
                }

                if (poin <= nilaiDiri) {
                    groupedData[groupKey].different++;
                }
                groupedData[groupKey].capaian++;
            });

            const Audits = Object.values(groupedData).map(res => {
                const totalItems = res.capaian;
                const differentCount = res.different;
                let Capaian = 0;

                if (totalItems > 0) {
                    Capaian = (differentCount / totalItems) * 100;
                    Capaian = parseFloat(Capaian.toFixed(2));
                }

                return {
                    id: res.idStandars,
                    jenisStandar: res.jenisStandar,
                    status: res.allProgressOne,
                    totalPoin: res.totalPoin,
                    capaian: Capaian
                };
            });

            Audits.value = Audits; // corrected to assign Audits to Audits.value

            filteredAudits.value = Audits; // simplified assignment

            console.log(Audits); // changed from Audits.value to Audits
        } else {
            console.error('Data returned is not an array:', resAudits.data);
        }
    } catch (error) {
        console.error('Error fetching data:', error);
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
const goBack = () => {
    if (authStore.idParams.param == "analysAdmin") {
        router.push({ name: 'LaporanAnalysAsAdmin'})
    } else {
        router.push({ name: 'LaporanAuditAsAdmin'})
    }
};

// Visual Method
const Visual = () => {
    router.push({ name: 'ChartComponent' });
};

// Detail Method
const detail = (idStandar, Standar, fakultas, prodi) => {
    const data = {
        ...authStore,
        idStandar: idStandar,
        jstandar: Standar,
        fakultas: fakultas,
        prodi: prodi,
    }
    authStore.scope(data);
    router.push({ name: "DetailLaporanAsAdmin" });
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
