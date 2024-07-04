<template v-if="authStore.role == 'auditor'">
    <div class="bg-gray-200 min-h-screen p-4">
        <div class="bg-white min-w-[100%] max-w-[100%] h-fit rounded-xl flex flex-col divide-y-2 shadow-md transition-all transition-duration-700 ease-in-out overflow-hidden">
            <!-- Header Start -->
            <div class="p-5">
                <h1 class="text-2xl font-bold">Assign Auditee for Audit</h1>
            </div>
            <!-- Header End -->

            <!-- Content Start -->
            <div id="audit" class="p-5 flex flex-col h-full justify-between overflow-y-auto">
                <div class="flex flex-col gap-5">
                    <!-- Select Option Start -->
                    <div class="grow-0 flex flex-col gap-5">
                        <!-- Tipe Audit -->
                        <div class="flex items-center justify-between">
                            <label for="tipeAudit" class="font-bold block mb-2">Pilih Tipe Audit</label>
                            <select v-model="form.TipeAudit" id="tipeAudit" required  class="border-2 border-[#cccccc] rounded p-1 w-1/2 capitalize" @change="unikstandar()">
                                <option disabled >Pilih Tipe Audit</option>
                                <option v-for="tipe in tipeAudit" :key="tipe.id" :value="tipe.id">{{ tipe.tipe_audit }} - {{tipe.fakultas.prodi}} - {{ tipe.periode }} - {{tipe.semester}}</option>
                            </select>
                        </div>

                        <!-- Standar -->
                        <div class="flex items-center justify-between">
                            <label for="tipeStandar" class="font-bold block mb-2">Pilih Standar</label>
                            <select v-model="form.standar" id="tipeStandar" required class="border-2 border-[#cccccc] rounded p-1 w-1/2" @change="Pernyataan">
                                <option v-for="standar in unikstandar()" :key="standar.standars.id" :value="standar.standars.id">{{ standar.standars.jenis_standar }}</option>
                            </select>
                        </div>

                        <!-- Fakultas -->
                        <div class="flex items-center justify-between">
                            <label for="fakultas" class="font-bold block mb-2">Pilih Fakultas Auditee</label>
                            <select v-model="form.fakultas" id="fakultas" required @change="user" class="border-2 border-[#cccccc] rounded p-1 w-1/2">
                                <option v-for="fk in unikFakultas" :key="fk" :value="fk">{{ fk }}</option>
                            </select>
                        </div>

                        <!-- Auditee -->
                        <div class="flex items-center justify-between">
                            <label for="user" class="font-bold block mb-2">Pilih Auditee</label>
                            <select v-model="form.user" id="user" required @change="CheckPernyataan(form.user.id)" class="border-2 border-[#cccccc] rounded p-1 w-1/2">
                                <option v-for="user in users" :key="user.id" :value="user">{{ user.name }} - {{ user.fakultas.prodi }}</option>
                            </select>
                        </div>
                    </div>
                    <!-- Select Option End -->

                    <!-- Select All Start -->
                    <div class="min-w-full flex items-end justify-between">
                        <!-- Select All -->
                        <div class="flex gap-2">
                            <label for="selectAll">Assign Semua Pernyataan</label>
                            <input type="checkbox" name="selectAll" @change="checkbox($event.target.checked)">
                        </div>
                        <!-- Unselect ALL Assign for auditee -->

                        <!-- Note -->
                        <div>
                            <p>Catatan:</p>
                            <p class="text-green-500">*Text berwarna hijau menunjukkan bahwa pernyataan sudah ditetapkan.</p>
                        </div>
                    </div>
                    <!-- Select All End -->

                    <!-- Table Start -->
                    <div class="grow min-w-full h-full overflow-y-auto inline-block align-middle flex flex-col gap-5">
                        <div class="overflow-auto border-gray-200 rounded-md shadow-md">
                            <table class="w-full rounded-md">
                                <thead>
                                <tr class="sticky top-0">
                                    <th class="sticky top-0 px-6 py-3 text-center bg-gray-200">No.</th>
                                    <th class="sticky top-0 px-6 py-3 text-start bg-gray-200">Pernyataan Standar</th>
                                    <th class="sticky top-0 px-6 py-3 text-center bg-gray-200">Nilai Maksimum</th>
                                    <th class="sticky top-0 px-6 py-3 text-center bg-gray-200">Aksi</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y">
                                <!-- Loop untuk setiap item standar dari database -->
                                <template v-if="matchedPernyataan.length > 0 && !cekStatus">
                                    <tr v-for="(standar, index) in matchedPernyataan" :key="index" class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                                        <td class="px-6 py-4 text-center text-gray-800">{{ index + 1 }}</td>
                                        <td class="px-6 py-4 text-gray-800">
                                            <span class="text-green-500" v-if="standar.referensi ">{{ standar.pernyataan_standar }}</span>
                                            <span v-else>{{ standar.pernyataan_standar }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-gray-800">
                                            <span>{{ standar.poin }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-gray-800">
                                            <div class="flex items-center justify-center">
                                                <div v-if="standar.status =='sudah'" @click="Unchecklist(standar.id)" class="flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                                    </svg>
                                                </div>
                                                <input type="checkbox" v-model="standar.check" v-else>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else-if="matchedPernyataan.length > 0 && cekStatus">
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 font-bold text-red-500 text-center">Standar sudah dikunci</td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr v-for="(standar, index) in pernyataan" :key="index" class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                                        <td class="px-6 py-4 text-center text-gray-800">{{ index + 1 }}</td>
                                        <td class="px-6 py-4 text-gray-800">
                                            <span>{{ standar.pernyataan_standar }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-gray-800">
                                            <span>{{ standar.poin }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-gray-800">
                                            <input type="checkbox" v-model="standar.check">
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="flex gap-2 justify-between">
                            <!-- Save Button -->
                            <button class="bg-blue-500 hover:bg-blue-700 w-fit py-2 px-4 rounded-md hover:cursor-pointer shadow-md text-white" @click="kirim" v-if="!cekStatus && chekcList() && load">Save</button>

                            <!-- Unasign Button -->
                            <button @click="unselect(form.user.id,form.TipeAudit,form.standar)" class="bg-blue-500 hover:bg-blue-700 w-fit py-2 px-4 rounded-md hover:cursor-pointer shadow-md text-white" v-if="matchedPernyataan.length > 0 && !cekStatus && load">Unasign</button>
                        </div>
                    </div>
                    <!-- Table End -->
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
// Import Library
import axios from "axios";
import { computed, onMounted, ref } from "vue";
import { useAuthStore } from "@/router/auth.js";

// Variable Declaration
const authStore = useAuthStore();
const form = ref({
    TipeAudit: null,
    standar: null,
    fakultas: null,
    user: null,
});
const users = ref([]);
const tipeAudit = ref([]);
const standars = ref([]);
const fakultas = ref([]);
const matchedPernyataan = ref([]);
const pernyataan = ref([]);
const cekStatus = ref(false);
const  load = ref(true);

// Retrieving User Data
const user = async () => {
    if (form.value.fakultas !== null) {
        try {
            const resUser = await axios.get(`${authStore.url}/users/${form.value.fakultas}`,authStore.header);
            users.value = resUser.data;
        } catch (e) {
            console.log(e);
        }
    }
};

// Checkbox Method
const checkbox = (status) => {
    if (matchedPernyataan.value && matchedPernyataan.value.length > 0) {
        matchedPernyataan.value.forEach(res => {
            res.check = status;
        })
    } else if (pernyataan.value && pernyataan.value.length > 0) {
        pernyataan.value.forEach(res => {
            res.check = status;
        })
    }
}

// Checking Statement
const CheckPernyataan = async (id_Fakultas) => {
    cekStatus.value = false;
    matchedPernyataan.value = [];
    try {
        // Mendapatkan data audit
        const auditResponse = await axios.get(
            `${authStore.url}/mystatement/${form.value.TipeAudit}/${form.value.standar}`,
            authStore.header
        );
        const auditData = auditResponse.data;

        // Mendapatkan data pernyataan
        const pernyataan = standars.value.filter(item => {
            return item.id_tipe_audits == form.value.TipeAudit && item.id_standars == form.value.standar;
        });

        // Memeriksa kecocokan id standar dengan id per standar
        const cekProgress = auditData.find(auditItem => auditItem.progress == 1);
        cekStatus.value = !!cekProgress;

        // Mencocokkan pernyataan dengan data audit
        matchedPernyataan.value = pernyataan.map(pernyataanItem => {
            const matchedAudit = auditData.find(auditItem => auditItem.id_per_standars == pernyataanItem.id);
            const auditee = matchedAudit && auditData.some(audit => audit.id_users == id_Fakultas && audit.id_per_standars == pernyataanItem.id && audit.id_tipe_audits == form.value.TipeAudit);

            return {
                ...pernyataanItem,
                check: false,
                referensi: auditee,
                status: matchedAudit ? 'sudah' : 'belum',
            };
        });

        console.log(matchedPernyataan.value);
    } catch (error) {
        console.error("Error checking pernyataan:", error);
    }
};

// Checklist Method
const chekcList = () => {
    if(matchedPernyataan.value.length > 0){
        const cekMe = matchedPernyataan.value.find(res => res.check);

        return cekMe ? true : false
    }else if(pernyataan.value.length > 0){
        const cekMe = pernyataan.value.find(res => res.check);

        return cekMe ? true : false
    }
}

// Send Data
const kirim = async () => {
    try {
        load.value = false;
        let idUser = form.value.user.id;
        // Filter item yang baru saja dicentang
        if(matchedPernyataan.value.length > 0){
            const newCheckedItems = matchedPernyataan.value.filter(standar => standar.status != 'sudah' && standar.check);
            // Buat array untuk menyimpan data yang akan dikirim
            const formData = newCheckedItems.map(standar => ({
                'id_per_standars': standar.id,
                'id_tipe_audits': standar.id_tipe_audits,
                'id_users':idUser
            }));

            // Kirim data menggunakan axios.post() dengan async/await
            await Promise.all(formData.map(async (formItem) => {
                await axios.post(`${authStore.url}/checkit`, formItem, authStore.header);
            }));
            load.value = true;
        }else{
            const newCheckedItems = pernyataan.value.filter(standar => standar.check);

            // Buat array untuk menyimpan data yang akan dikirim
            const formData = newCheckedItems.map(standar => ({
                'id_per_standars': standar.id,
                'id_tipe_audits': standar.id_tipe_audits,
                'id_users': idUser
            }));

            // Kirim data menggunakan axios.post() dengan async/await
            await Promise.all(formData.map(async (formItem) => {
                await axios.post(`${authStore.url}/checkit`, formItem,authStore.header);
            }));
            load.value=true;
        }
        CheckPernyataan(form.value.user.id);
    } catch (error) {
        console.error("Error sending data:", error);
    }
};

// OnMounted Method
onMounted(async () => {
    try {
        const [resTipe,resStandar, resFakultas] = await Promise.all([
            axios.get(`${authStore.url}/myTipeAudit/${authStore.uid}`, authStore.header),
            axios.get(`${authStore.url}/perstandars/${authStore.uid}`, authStore.header),
            axios.get(`${authStore.url}/fakultas`, authStore.header)
        ]);

        tipeAudit.value = resTipe.data;
        standars.value = resStandar.data;
        fakultas.value = resFakultas.data;
    } catch (error) {
        console.error("Error fetching data:", error);
    }
});

document.title = `Assign Auditee | ${authStore.name}`;

// Retrieving Fakultas Data
const unikFakultas = computed(() => {
    const fakultasSet = new Set();
    fakultas.value.forEach((res) => {
        if (res.fakultas === "Universitas Advent Indonesia") {
            return;
        }
        fakultasSet.add(res.fakultas);
    });

    return Array.from(fakultasSet);
});

// Retrieving Standar Data
const unikstandar = () => {
    const seen = new Map();
    return standars.value.filter(standar => {
        if (standar.id_tipe_audits == form.value.TipeAudit) {
            const duplicate = seen.has(standar.id_standars);

            if (!duplicate) {
                seen.set(standar.id_standars, true);
            }

            return !duplicate;
        }
    });

};

// Filter Statement
const Pernyataan = () => {
    pernyataan.value = standars.value
        .filter(item => item.id_tipe_audits == form.value.TipeAudit && item.id_standars == form.value.standar)
        .map(pernyataanItem => ({
            ...pernyataanItem,
            check: false,
        }));
    CheckPernyataan("1");
};

// Unchecklist Method
const Unchecklist = (id) => {
    if (confirm("Ingin Membatalkan Pernyataan untuk di tetapkan ke User tersebut?")) {
        axios.delete(`${authStore.url}/uncheck/${id}`, authStore.header);
    }

    CheckPernyataan(form.value.user.id_fakultas);
}

// Unselect Method
const unselect = (idUser, idTipe, idStandar) => {
    const req = `${idUser}/${idTipe}/${idStandar}`;
    if(confirm("Ingin Membatalkan Pernyataan untuk ditetapkan ke User tersebut?")){
        axios.delete(`${authStore.url}/allUncheck/${req}`,authStore.header);
    }
    CheckPernyataan(form.value.user.id_fakultas);
}
</script>
