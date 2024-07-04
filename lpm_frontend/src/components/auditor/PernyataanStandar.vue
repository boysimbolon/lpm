<template v-if="authStore.role === 'auditor'">
    <div class="flex bg-gray-200 min-h-screen p-4 gap-5">
        <div class="bg-white min-w-[100%] max-w-[100%] h-full rounded-xl flex flex-col divide-y-2 shadow-md transition-all transition-duration-700 ease-in-out">
            <!-- Header Start -->
            <div class="p-5">
                <h1 class="text-2xl font-bold">Form Pernyataan Standar</h1>
            </div>
            <!-- Header End -->

            <!-- Content Start -->
            <div id="audit" class="p-5 flex flex-col gap-5">
                <!-- Select Option Start -->
                <div class="flex flex-col gap-5">
                    <!-- Tipe Audit -->
                    <div class="flex items-center justify-between">
                        <label for="tipeAudit" class="font-semibold mb-2 w-1/2">Pilih Tipe Audit</label>
                        <select v-model="form.TipeAudit" id="tipeAudit" required class="border-2 border-[#cccccc] rounded p-1 w-1/2 capitalize" @change="unikstandar()">
                            <option disabled >Pilih Tipe Audit</option>
                            <option v-for="tipe in tipeAudit" :key="tipe.id" :value="tipe.id">{{ tipe.tipe_audit }} -{{ tipe.fakultas.prodi }}- {{ tipe.periode }} - {{ tipe.semester }}</option>
                        </select>
                    </div>

                    <!-- Standar -->
                    <div class="flex items-center justify-between">
                        <label for="tipeStandar" class="font-semibold mb-2 w-1/2">Pilih Standar</label>
                        <select v-model="form.standar" id="tipeStandar" required class="border-2 border-[#cccccc] rounded p-1 w-1/2" @change="updateRows">
                            <option v-for="standar in unikstandar()" :key="standar.standars.id" :value="standar.standars.id">{{ standar.standars.jenis_standar }}</option>
                        </select>
                    </div>
                </div>
                <!-- Select Option End -->

                <!-- Table Start -->
                <div class="min-w-full inline-block align-middle h-full flex flex-col gap-5">
                    <div class="flex overflow-auto border-gray-200 rounded-md shadow-md">
                        <table class="w-full rounded-md">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 w-[5%] text-center bg-gray-200">No.</th>
                                    <th class="px-6 py-3 w-[70%] text-start bg-gray-200">Pernyataan Standar</th>
                                    <th class="px-6 py-3 w-[15%] text-center bg-gray-200">Nilai Maksimum</th>
                                    <th class="px-6 py-3 w-[10%] text-center bg-gray-200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                <!-- Loop untuk setiap item standar dari database -->
                                <tr v-for="(baris, index) in rows" :key="baris" class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                                    <td class="px-6 py-4 w-[5%] text-gray-800 text-center">{{ index + 1 }}</td>
                                    <td class="px-6 py-4 w-[70%] text-gray-800 text-start">
                                        <textarea type="text" v-model="baris.Pernyataan" v-if="baris.pernyataan === null" class="p-2 rounded border border-black border-solid w-full"></textarea>
                                        <span v-else>{{ baris.pernyataan }}</span>
                                    </td>
                                    <td class="px-6 py-4 w-[15%] text-gray-800 text-center">
                                        <!-- Input nilai diri jika dokumen belum diunggah -->
                                        <input type="number" min="0" v-model="baris.Poin" v-if="baris.poin === null" class="rounded border border-black border-solid w-full">
                                        <span v-else>{{ baris.poin }}</span>
                                    </td>
                                    <td class="px-6 py-4 w-[10%] text-gray-800 text-center">
                                        <!-- Tombol Save untuk menyimpan jika dokumen belum diunggah -->
                                        <button @click="kirim(index)" v-if="baris.pernyataan === null || baris.poin === null" class="bg-blue-500 text-white py-2 px-4 rounded-md cursor-pointer hover:bg-blue-700">Save</button>
                                        <button v-else class="bg-green-500 rounded-md text-center cursor-pointer hover:bg-green-700 text-white py-2 px-4" @click="Edit(baris, form.TipeAudit, form.standar)">Edit</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Add & Delete Row -->
                    <div class="flex gap-5">
                        <div class="flex gap-2 items-center">
                            <label class="font-semibold">Tambah Baris</label>
                            <input v-model="tabel.add" type="number" :min="1" @input="rowsUpdate('tambah')" class="py-2 px-4 rounded border border-black border-solid">
                        </div>
                        <div class="flex gap-2 items-center">
                            <label class="font-semibold">Hapus baris</label>
                            <input v-model="tabel.delete" type="number" :min="1" @input="rowsUpdate('kurang')" class="py-2 px-4 rounded border border-black border-solid">
                        </div>
                    </div>
                </div>
                <!-- Table End -->
            </div>
            <!-- Content End -->
        </div>
    </div>
</template>

<script setup>
// Import Library
import axios from "axios";
import { onMounted, ref } from "vue";
import { useAuthStore } from "@/router/auth.js";
import router from "@/router/index.js";

// Variable Declaration
const authStore = useAuthStore();
const form = ref({
    TipeAudit: null,
    standar: null
});
const tipeAudit = ref([]);
const standars = ref([]);
const tabel = ref({
    add: null,
    delete: null
});
const rows = ref([]);
document.title = `Create Pernyataan | ${authStore.name}`;

// Update Rows Method
const updateRows = async () => {
    const tipeAudit = form.value.TipeAudit;
    const standar = form.value.standar;
    rows.value = [];

    if (standar !== null && tipeAudit !== null) {
        try {
            const response = await axios.get(`${authStore.url}/per-standars/${tipeAudit}/${standar}`,authStore.header);

            const responseData = response.data;

            // Bersihkan rows sebelum menambahkan data baru
            // Panggil fungsi untuk menambahkan data ke rows
            responseData.forEach(item => {
                rows.value.push({pernyataan: item.pernyataan_standar, poin: item.poin, id:item.id});
            });
        } catch (error) {
            console.error("Error fetching rows data:", error);
        }
    }
};

// Rows Update Method
const rowsUpdate = (param) => {
    let datas = rows.value.filter(e => e.pernyataan !== null || e.poin !== null || e.Poin !== null || e.Pernyataan !== null).length;
    const targetLength = tabel.value;

    if (param === "tambah") {
        // Tambah baris tambahan jika diperlukan
        for (let i = 0; i < targetLength.add; i++) {
            rows.value.push({Pernyataan: null, Poin: null, pernyataan: null, poin: null});
        }
    } else if (param === "kurang") {
        // Kurangi baris jika jumlah data kurang dari targetLength
        rows.value.splice(datas, targetLength.delete);
    }
};

// Send Data Method
const kirim = async (index) => {
    const item = rows.value[index];
    const formItem = {
        'id_standars': form.value.standar,
        'id_tipe_audits': form.value.TipeAudit,
        'pernyataan_standar': item.Pernyataan,
        'poin': item.Poin
    };
    try {
        // Kirim data menggunakan axios.post() dengan async/await
        await axios.post(`${authStore.url}/perStandars`, formItem, authStore.header);
        updateRows();
    } catch (error) {
        console.error("Error sending data:", error);
    }
};

// OnMounted Method
onMounted(async () => {
    try {
        if (authStore.idParams !== null && authStore.idParams !== undefined) {
            if (authStore.idParams?.tipeAuditParam !== null && authStore.idParams?.standarParam !== null) {
                form.value.TipeAudit = authStore.idParams.tipeAuditParam;
                form.value.standar = authStore.idParams.standarParam;
                unikstandar();
                await updateRows();
                authStore.idParams.tipeAuditParam = null;
                authStore.idParams.standarParam = null;
            } else {
                form.value.TipeAudit = null;
                form.value.standar = null;
            }
        }
            const [resTipe,resStandar] = await Promise.all([
                axios.get(`${authStore.url}/tipeAudits`, authStore.header),
                axios.get(`${authStore.url}/per-standars`, authStore.header)
            ]);

            tipeAudit.value = resTipe.data;
            standars.value = resStandar.data;

    } catch (error) {
        console.error("Error fetching data:", error);
    }
});

// Unique Standar Method
const unikstandar = () => {
    const seen = new Map();

    return standars.value.filter(standar => {
        if(standar.id_tipe_audits == form.value.TipeAudit){
            const duplicate = seen.has(standar.id_standars);
            if (!duplicate) {
                seen.set(standar.id_standars, true);
            }
            return !duplicate;
        }
    });
};

// Edit Method
const Edit = (standar, tipeAuditParam, standarParam) => {
    const data = {
        standar: standar,
        tipeAuditParam: tipeAuditParam,
        standarParam: standarParam
    };
    authStore.scope(data);
    console.log(authStore);
    router.push({ name: 'UpdatePernyataanStandarAsAuditor' });
}
</script>
