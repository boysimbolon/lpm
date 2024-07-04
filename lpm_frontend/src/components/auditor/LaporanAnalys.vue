<template v-if="authStore.role === 'auditor'">
    <div class="bg-gray-200 min-h-screen h-fit p-4">
        <div class="w-full bg-white rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex">
                <h1 class="text-2xl font-bold">Analysis Audit</h1>
            </div>
            <!-- Header End -->

            <!-- Periode A -->
            <div class="flex p-4 justify-between gap-5">
                <!-- Tahun Ajaran A -->
                <div class="flex w-1/2">
                    <label class="text-lg font-semibold w-1/2" for="tahunAjaranA">Tahun Ajaran A</label>
                    <select class="rounded p-1 border-2 border-solid border-[#cccccc] w-1/2" v-model="form.tahunA" @change="filter(form.tahunA,form.semesterA,form.tahunB,form.semesterB)" name="tahunAjaranA">
                        <option v-for="period in generatePeriodeOptions" :key="period" :value="period">{{ period }}</option>
                    </select>
                </div>

                <!-- Semester A -->
                <div class="flex w-1/2">
                    <label class="text-lg font-semibold w-1/2" for="semesterA">Semester A</label>
                    <select class="rounded p-1 border-2 border-solid border-[#cccccc] w-1/2" v-model="form.semesterA" @change="filter(form.tahunA,form.semesterA,form.tahunB,form.semesterB)" name="semesterA">
                        <option value="genap">Genap</option>
                        <option value="ganjil">Ganjil</option>
                    </select>
                </div>
            </div>

            <!-- Periode B -->
            <div class="flex p-4 justify-between gap-5">
                <!-- Tahun Ajaran B -->
                <div class="flex w-1/2">
                    <label class="text-lg font-semibold w-1/2" for="tahunAjaranB">Tahun Ajaran B</label>
                    <select class="rounded p-1 border-2 border-solid border-[#cccccc] w-1/2" v-model="form.tahunB" @change="filter(form.tahunA,form.semesterA,form.tahunB,form.semesterB)" name="tahunAjaranB">
                        <option v-for="period in generatePeriodeOptions" :key="period" :value="period">{{ period }}</option>
                    </select>
                </div>

                <!-- Semester B -->
                <div class="flex w-1/2">
                    <label class="text-lg font-semibold w-1/2" for="semesterB">Semester B</label>
                    <select class="rounded p-1 border-2 border-solid border-[#cccccc] w-1/2" v-model="form.semesterB" @change="filter(form.tahunA,form.semesterA,form.tahunB,form.semesterB)" name="semesterB">
                        <option value="genap">Genap</option>
                        <option value="ganjil">Ganjil</option>
                    </select>
                </div>
            </div>

            <!-- Table Start -->
            <div class="p-4 overflow-auto">
                <!-- Filter Prodi -->
                <div class="flex items-center justify-end">
                    <select class="rounded p-1 border-2 border-solid border-[#cccccc]" v-model="filterProdi" @change="filter(form.tahunA,form.semesterA,form.tahunB,form.semesterB,'tipe',filterProdi)" name="filterProdi">
                        <option value="ALL">All</option>
                        <option v-for="fak in fakultas" :value="fak.prodi">{{ fak.prodi }}</option>
                    </select>
                </div>

                <table class="table-auto text-left w-full">
                    <thead>
                    <tr>
                        <th class="p-4 w-[5%] text-center">No.</th>
                        <th class="p-4 w-[20%] text-nowrap" @click="filter(form.tahunA,form.semesterA,form.tahunB,form.semesterB,'tipe')">Tipe Audit</th>
                        <th class="p-4 w-[20%]" @click="filter(form.tahunA,form.semesterA,form.tahunB,form.semesterB,'standar')">Standar</th>
                        <th class="p-4 w-[10%] text-center">Total Nilai Diri A</th>
                        <th class="p-4 w-[10%] text-center">Total Nilai Diri B</th>
                        <th class="p-4 w-[10%] text-center">Total Nilai Max A</th>
                        <th class="p-4 w-[10%] text-center">Total Nilai Max B</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="Audits.length > 0">
                        <tr v-for="(audit, auditIndex) in Audits" :key="auditIndex" class="border-y">
                            <td class="p-4 w-[5%] text-center">{{ auditIndex + 1 }}</td>
                            <td class="p-4 w-[20%]">{{ audit.tipeAudit }} - {{audit.prodi}}</td>
                            <td class="p-4 w-[20%]">{{ audit.standar }}</td>
                            <td class="p-4 w-[10%] text-center">{{ audit.totalPoinA }}</td>
                            <td class="p-4 w-[10%] text-center">{{ audit.totalPoinB }}</td>
                            <td class="p-4 w-[10%] text-center">{{ audit.totalMaxA }}</td>
                            <td class="p-4 w-[10%] text-center">{{ audit.totalMaxB }}</td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr class="border-y">
                            <td colspan="7" class="p-4 text-center">Belum Ada Audit yang berhasil di kunci</td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
            <!-- Table End -->
        </div>
    </div>
</template>

<script setup>
// Import Library
import { ref, onMounted, computed } from 'vue';
import { useAuthStore } from '@/router/auth.js';
import axios from 'axios';
import router from "@/router/index.js";

// Variable Declaration
const Audits = ref([]);
const authStore = useAuthStore();
const form = ref({
    tahunA: null,
    semesterA: null,
    tahunB: null,
    semesterB: null
})
const filterProdi = ref('');
const auditData = ref(null);
const nilai = ref(null);
const standarData = ref(null);
const fakultas = ref([]);
// OnMounted Method
onMounted(async () => {
    try {
        // Mengambil data audits dan standars secara bersamaan
        const [resAudits, resStandars, resFakul] = await Promise.all([
            axios.get(`${authStore.url}/audits`, authStore.header),
            axios.get(`${authStore.url}/standars`, authStore.header),
            axios.get(`${authStore.url}/fakultas`,authStore.header)
        ]);

        // Menyusun data audits
        auditData.value = resAudits.data;
        standarData.value = resStandars.data;
        fakultas.value = resFakul.data;
        filterProdi.value = "ALL"
        form.value.semesterA = "ganjil"
        form.value.tahunA = "2022/2023"
        form.value.semesterB = "genap"
        form.value.tahunB = "2022/2023"
        filter(form.value.tahunA,form.value.semesterA,form.value.tahunB,form.value.semesterB,"tipe",filterProdi.value)
    } catch (error) {
        console.error('Error fetching data:', error);
    }
});

// Request Method
const req = (audit, Standar, sort,filter) => {
    // Objek untuk menyimpan hasil pengelompokan
    const auditGroupedByTipeStandar = {};

    audit.forEach(auditItem => {
        const idStandar = auditItem.per_standars.id_standars;
        const standar = Standar.find(res => res.id == idStandar);
        const tipeAudit = auditItem.tipe_audits.tipe_audit;
        const fakultas = auditItem.tipe_audits.fakultas.prodi;
        const standarName = standar ? standar.jenis_standar : 'Standar tidak ditemukan';
        const periode = auditItem.tipe_audits.periode;
        const semester = auditItem.tipe_audits.semester;
        const nilaiDiri = parseFloat(auditItem.nilai_diri);
        const nilaiMax = parseFloat(auditItem.per_standars.poin);

        const key = `${fakultas}-${standarName}`;

        if (!auditGroupedByTipeStandar[key]) {
            auditGroupedByTipeStandar[key] = {
                tipeAudit: tipeAudit,
                prodi: fakultas,
                standar: standarName,
                totalPoinA: 0,
                totalPoinB: 0,
                totalMaxA: 0,
                totalMaxB: 0,
            };
        }

        // Menentukan apakah nilai ini termasuk dalam total poin A atau B berdasarkan form input
        if (periode == form.value.tahunA && semester == form.value.semesterA) {
            auditGroupedByTipeStandar[key].totalPoinA += nilaiDiri;
            auditGroupedByTipeStandar[key].totalMaxA += nilaiMax;
        } else if (periode == form.value.tahunB && semester == form.value.semesterB) {
            auditGroupedByTipeStandar[key].totalPoinB += nilaiDiri;
            auditGroupedByTipeStandar[key].totalMaxB += nilaiMax;
        }
    });

    // Mengubah objek hasil pengelompokan menjadi array
    let result = Object.values(auditGroupedByTipeStandar).map(item => ({
        tipeAudit: item.tipeAudit,
        prodi: item.prodi,
        standar: item.standar,
        totalPoinA: item.totalPoinA.toFixed(2),
        totalPoinB: item.totalPoinB.toFixed(2),
        totalMaxA: item.totalMaxA.toFixed(2),
        totalMaxB: item.totalMaxB.toFixed(2),
    }));

    // Mengembalikan hasil yang sudah diurutkan berdasarkan sort
    if (sort == 'tipe') {
        result.sort((a, b) => a.tipeAudit.localeCompare(b.tipeAudit)); // Sortir berdasarkan tipeAudit
    } else {
        result.sort((a, b) => a.standar.localeCompare(b.standar)); // Sortir berdasarkan standar
    }
    if (filter === "ALL") {
        Audits.value = result;
    } else {
        console.log(filter)
        Audits.value = result.filter(res => res.prodi === filter);
    }
};



// Filter Method
const filter = (periodeA, semesterA, periodeB, semesterB, sort,filter) => {
    nilai.value = [];
    if(periodeA != null && semesterA != null && periodeB != null && semesterB != null) {
        auditData.value.forEach(res => {
            const { periode, semester } = res.tipe_audits;
            if ((periode == periodeA && semester == semesterA) || (periode == periodeB && semester == semesterB)) {
                nilai.value.push(res);
            }
        });
    }
    req(nilai.value, standarData.value, sort,filter);
};

// Generate Periode Options Method
const generatePeriodeOptions = computed(() => {
    const tahun = 2020;
    const periode = [];
    for (let i = 0; i < 10; i++) {
        const startYear = tahun + i;
        const endYear = startYear + 1;
        const periodeText = `${startYear}/${endYear}`;
        periode.push(periodeText);
    }
    return periode;
});

// Detail Method
const Detail = (tipe, standar, tipeAudit,jstandar,periode,semester) => {
    const data = {
        idTipe: parseInt(tipe, 10),
        idStandar: standar,
        tipeAudit: tipeAudit,
        periode: periode,
        semester: semester,
        jstandar: jstandar,
        param: "analysAuditor"
    }

    authStore.scope(data);
    router.push({ name: 'DetailLaporanAsAuditor' });
}
</script>
