<template v-if="authStore.role === 'admin'">
    <div class="bg-gray-200 min-h-screen h-fit p-4">
        <div class="w-full bg-white rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Laporan Audit</h1>
                <div class="flex items-center gap-4 w-fit">
                    <!-- Tahun Ajaran -->
                    <div class="flex items-center gap-4 w-fit">
                        <label for="tahunAjaran" class="text-lg font-semibold text-nowrap">Tahun Ajaran:</label>
                        <select name="tahunAjaran" class="rounded border-2 border-[#cccccc] w-1/2 p-1" v-model="form.tahun" @change="filter(form.tahun,form.semester)">
                            <option v-for="period in generatePeriodeOptions" :key="period" :value="period">{{ period }}</option>
                        </select>
                    </div>

                    <!-- Semester -->
                    <div class="flex items-center gap-4 w-fit">
                        <label for="semester" class="text-lg font-semibold">Semester:</label>
                        <select name="semester" class="rounded border-2 border-[#cccccc] w-2/3 p-1" v-model="form.semester" @change="filter(form.tahun,form.semester)">
                            <option value="ALL">Semua</option>
                            <option value="genap">Genap</option>
                            <option value="ganjil">Ganjil</option>
                        </select>
                    </div>
                </div>

                <!-- Visualization Button -->
                <button @click="Visual(form.tahun,form.semester,Audits[0].tipeAudit)" class="flex items-center gap-2 bg-blue-500 hover:bg-blue-700 shadow-md text-white py-2 px-4 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-graph-up-arrow" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5"/>
                    </svg>
                    Visualisasi
                </button>

            </div>
            <!-- Header End -->

            <!-- Table Start -->
            <div class="p-4 overflow-auto">
                <table class="table-auto text-left w-full align-middle">
                    <thead>
                        <tr>
                            <th class="p-4 w-[5%] text-center">No.</th>
                            <th class="p-4 w-[20%] text-nowrap">Tipe Audit</th>
                            <th class="p-4 w-[15%]">Fakultas</th>
                            <th class="p-4 w-[15%]">Prodi</th>
                            <th class="p-4 w-[15%]">Auditee</th>
                            <th class="p-4 w-[10%] text-center">Total Nilai Diri</th>
                            <th class="p-4 w-[10%] text-center">Total Nilai Max</th>
                            <th class="p-4 w-[10%] text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="Audits.length > 0">
                            <tr v-for="(audit, auditIndex) in Audits" :key="auditIndex" class="border-y">
                                <td class="p-4 w-[5%] text-center">{{ auditIndex + 1 }}</td>
                                <td class="p-4 w-[20%] underline hover:cursor-pointer" v-if="form.semester !== 'ALL'" @click="Detail(audit.idTipeAudit, audit.tipeAudit, audit.fakultas, audit.prodi, form.tahun, form.semester)">{{ audit.tipeAudit }}</td>
                                <td class="p-4 w-[15%]" v-else>{{ audit.tipeAudit }}</td>
                                <td class="p-4 w-[15%] ">{{ audit.fakultas }}</td>
                                <td class="p-4 w-[15%] ">{{ audit.prodi }}</td>
                                <td class="p-4 w-[15%]">
                                    <template v-for="(user, userIndex) in audit.users" :key="userIndex">
                                        <span :class="{ 'text-red-500': user.status == '0' }">{{ userIndex + 1 }}. {{ user }}</span><br>
                                    </template>
                                </td>
                                <td class="p-4 w-[10%] text-center">{{ audit.totalPoin.toFixed(2) }}</td>
                                <td class="p-4 w-[10%] text-center">{{ audit.totalMax }}</td>
                                <td class="p-4 w-[10%] text-center text-white">
                                    <span v-if="audit.status == '0'" class="py-2 px-4 bg-red-500 rounded w-fit text-white">OnProgress</span>
                                    <span v-else class="py-2 px-4 bg-green-500 rounded w-fit">Selesai</span>
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr class="border-y">
                                <td colspan="8" class="p-4 text-center">Belum Ada Audit yang berhasil di kunci</td>
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
    tahun: null,
    semester: null
});
const auditData = ref(null);
const nilai = ref(null);
const standarData = ref(null);
document.title = `Laporan Audit | ${authStore.name}`;

// OnMounted Method
onMounted(async () => {
    try {
        // Mengambil data audits dan standars secara bersamaan
        const [resAudits, resStandars] = await Promise.all([
            axios.get(`${authStore.url}/audits`, authStore.header),
            axios.get(`${authStore.url}/standars`,authStore.header),
        ]);

        // Menyusun data audits
        auditData.value = resAudits.data;
        standarData.value = resStandars.data;
        if(form.value.semester == null && authStore.idParams == null){
            form.value.semester= "ALL"
            form.value.tahun = "2022/2023"
        } else {
            form.value.semester= authStore.idParams.semester
            form.value.tahun = authStore.idParams.tahun
        }
        filter(form.value.tahun, form.value.semester);

    } catch (error) {
        console.error('Error fetching data:', error);
    }
});

// Request Method
const req = (audit, Standar) => {
    const auditGroupedByTipeAudit = {};

    if (form.value.semester == "ALL") {
        // grouping audit data based on audit type
        audit.forEach(auditItem => {
            const idTipeAudit = `${auditItem.tipe_audits.tipe_audit}-${auditItem.tipe_audits.fakultas.id}-${form.value.tahun}`;

            if (!auditGroupedByTipeAudit[idTipeAudit]) {
                auditGroupedByTipeAudit[idTipeAudit] = {
                    idTipeAudit: idTipeAudit,
                    tipeAudit: auditItem.tipe_audits.tipe_audit,
                    fakultas: auditItem.tipe_audits.fakultas.fakultas,
                    prodi: auditItem.tipe_audits.fakultas.prodi,
                    users: [],
                    totalMax: 0,
                    totalPoin: 0,
                    status: 1,
                };
            }

            // Update status if progress is '0'
            if (auditItem.progress === '0') {
                auditGroupedByTipeAudit[idTipeAudit].status = 0;
            }

            const userName = auditItem.users.name;

            if (!auditGroupedByTipeAudit[idTipeAudit].users.includes(userName)) {
                auditGroupedByTipeAudit[idTipeAudit].users.push(userName);
            }

            // Ensure the points are added as numbers
            auditGroupedByTipeAudit[idTipeAudit].totalMax += parseFloat(auditItem.per_standars.poin) || 0;
            auditGroupedByTipeAudit[idTipeAudit].totalPoin += parseFloat(auditItem.nilai_diri) || 0;
        });

    } else {
        audit.forEach(auditItem => {
            const idTipeAudit = auditItem.id_tipe_audits;
            const idStandar = auditItem.per_standars.id_standars;
            const standar = Standar.find(res => res.id == idStandar);

            if (standar) {
                if (!auditGroupedByTipeAudit[idTipeAudit]) {
                    auditGroupedByTipeAudit[idTipeAudit] = {
                        idTipeAudit: idTipeAudit,
                        tipeAudit: auditItem.tipe_audits.tipe_audit,
                        fakultas: auditItem.tipe_audits.fakultas.fakultas,
                        prodi: auditItem.tipe_audits.fakultas.prodi,
                        users: [],
                        totalMax: 0,
                        totalPoin: 0,
                        status: 1,
                    };
                }

                // Update status if progress is '0'
                if (auditItem.progress === '0') {
                    auditGroupedByTipeAudit[idTipeAudit].status = 0;
                }

                const userName = auditItem.users.name;

                if (!auditGroupedByTipeAudit[idTipeAudit].users.includes(userName)) {
                    auditGroupedByTipeAudit[idTipeAudit].users.push(userName);
                }

                // Ensure the points are added as numbers
                auditGroupedByTipeAudit[idTipeAudit].totalMax += parseFloat(auditItem.per_standars.poin) || 0;
                auditGroupedByTipeAudit[idTipeAudit].totalPoin += parseFloat(auditItem.nilai_diri) || 0;
            }
        });
    }

    // Convert the grouped results into an array if necessary
    const resultArray = Object.values(auditGroupedByTipeAudit);
    Audits.value = resultArray;
};

// Filter Method
const filter = (periode, semester) => {
    nilai.value = [];
    if(semester == 'ALL') {
        // filter audit data by period
        auditData.value.forEach(res => {
            if(res.tipe_audits.periode == periode){
                nilai.value.push(res);
            }
        })
    } else {
        // filter audit data by period and semester
        auditData.value.forEach(res => {
            if(res.tipe_audits.periode == periode && res.tipe_audits.semester == semester){
                nilai.value.push(res);
            }
        });
    }
    req(nilai.value, standarData.value);
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
const Detail = (idtipe, tipe, fakultas, prodi, tahun, semester) => {
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
}

// Visual Method
const Visual = (periode, semester, tipeAudit) => {
    const data = {
        tahun: periode,
        semester: semester,
        tipeAudit: tipeAudit
    }
    authStore.scope(data);
    router.push({ name: 'VisualAuditAsAdmin' });
};
</script>
