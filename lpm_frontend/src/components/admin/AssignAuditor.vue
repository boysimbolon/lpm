<template v-if="authStore.role === 'admin'">
    <div class="bg-gray-200 min-h-screen p-4">
        <div class="bg-white min-w-[100%] max-w-[100%] h-fit rounded-xl flex flex-col divide-y-2 shadow-md transition-all transition-duration-700 ease-in-out overflow-hidden">
            <!-- Header Start -->
            <div class="p-5">
                <h1 class="text-2xl font-bold">Assign Auditor</h1>
            </div>
            <!-- Header End -->

            <!-- Content Start -->
            <div id="audit" class="p-5 flex flex-col h-full justify-between overflow-y-auto">
                <div class="flex flex-col gap-5">
                    <!-- Select Option Start -->
                    <div class="grow-0 flex flex-col gap-5">
                        <div class="flex items-center justify-between">
                            <label for="user" class="font-bold block mb-2">Pilih Auditor</label>
                            <select v-model="form.user" id="user" required @change="CheckPernyataan(form.user)" class="border-2 border-[#cccccc] rounded p-1 w-1/2">
                                <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                            </select>
                        </div>
                    </div>
                    <!-- Select Option End -->

                    <!-- Table Start -->
                    <div class="grow min-w-full h-full overflow-y-auto inline-block align-middle flex flex-col gap-5">
                        <div class="overflow-auto border-gray-200 rounded-md shadow-md">
                            <table class="w-full rounded-md">
                                <thead>
                                    <tr class="sticky top-0">
                                        <th class="sticky top-0 px-6 py-3 w-[5%] text-center bg-gray-200">No.</th>
                                        <th class="sticky top-0 px-6 py-3 w-[25%] text-start bg-gray-200">Tipe Audit</th>
                                        <th class="sticky top-0 px-6 py-3 w-[25%] text-start bg-gray-200">Fakultas</th>
                                        <th class="sticky top-0 px-6 py-3 w-[20%] text-start bg-gray-200">Prodi</th>
                                        <th class="sticky top-0 px-6 py-3 w-[10%] text-center bg-gray-200">Tahun Ajaran</th>
                                        <th class="sticky top-0 px-6 py-3 w-[10%] text-center bg-gray-200">Semester</th>
                                        <th class="sticky top-0 px-6 py-3 w-[5%] text-center bg-gray-200">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                <!-- Loop untuk setiap item standar dari database -->
                                <template v-if="matchedPernyataan.length > 0">
                                    <tr v-for="(standar, index) in matchedPernyataan" :key="index" class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                                        <td class="px-6 py-4 w-[5%] text-center text-gray-800">{{ index + 1 }}</td>
                                        <td class="px-6 py-4 w-[25%] text-gray-800">
                                            <span>{{ standar.tipe_audit }}</span>
                                        </td>
                                        <td class="px-6 py-4 w-[25%] text-start text-gray-800">
                                            <span>{{ standar.fakultas.fakultas }}</span>
                                        </td>
                                        <td class="px-6 py-4 w-[20%] text-start text-gray-800">
                                            <span>{{ standar.fakultas.prodi }}</span>
                                        </td>
                                        <td class="px-6 py-4 w-[10%] text-center text-gray-800">
                                            <span>{{ standar.periode }}</span>
                                        </td>
                                        <td class="px-6 py-4 w-[10%] text-center text-gray-800 capitalize">
                                            <span>{{ standar.semester }}</span>
                                        </td>
                                        <td class="px-6 py-4 w-[5%] text-center text-gray-800">
                                            <div v-if="standar.status =='sudah'" @click="Unchecklist(standar.id)" class="flex justify-center cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                                </svg>
                                            </div>
                                            <input type="checkbox" v-model="standar.check" v-else>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr v-for="(standar, index) in tipeAudit" :key="index" class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                                        <td class="px-6 py-4 w-[5%] text-center text-gray-800">{{ index + 1 }}</td>
                                        <td class="px-6 py-4 w-[25%] text-gray-800">
                                            <span>{{ standar.tipe_audit }}</span>
                                        </td>
                                        <td class="px-6 py-4 w-[25%] text-start text-gray-800">
                                            <span>{{ standar.fakultas.fakultas }}</span>
                                        </td>
                                        <td class="px-6 py-4 w-[20%] text-start text-gray-800">
                                            <span>{{ standar.fakultas.prodi }}</span>
                                        </td>
                                        <td class="px-6 py-4 w-[10%] text-center text-gray-800">
                                            <span>{{ standar.periode }}</span>
                                        </td>
                                        <td class="px-6 py-4 w-[10%] text-center text-gray-800 capitalize">
                                            <span>{{ standar.semester }}</span>
                                        </td>
                                        <td class="px-6 py-4 w-[5%] text-center text-gray-800">
                                            <input type="checkbox" v-model="standar.check">
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Save Button -->
                        <div class="w-full flex justify-end">
                            <button class="bg-blue-500 hover:bg-blue-700 w-fit py-2 px-4 rounded-md hover:cursor-pointer shadow-md text-white" @click="kirim" v-if=" load">Save</button>
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
import { onMounted, ref } from "vue";
import { useAuthStore } from "@/router/auth.js";

// Variable Declaration
const authStore = useAuthStore();
const form = ref({
    user: null,
});
const users = ref([]);
const tipeAudit = ref([]);
const matchedPernyataan = ref([]);
const load = ref(true);

// Retrieving User Data

// Checking Statement
const CheckPernyataan = async (id) => {
    matchedPernyataan.value = [];
    try {
        if (id != null) {
            // Memeriksa kecocokan id standar dengan id per standar
            matchedPernyataan.value = tipeAudit.value.map(pernyataanItem => {
                // Memastikan idLPM ada dan merupakan array sebelum memanggil includes
                if(pernyataanItem.idLPM != null){
                    const matchedAudit =  pernyataanItem.idLPM.includes(id);
                    return {
                        ...pernyataanItem,
                        check: matchedAudit, // true jika idLPM ditemukan, false jika tidak
                        status: matchedAudit ? 'sudah' : 'belum',
                    };
                }else{
                    return {
                        ...pernyataanItem,
                        check: false, // true jika idLPM ditemukan, false jika tidak
                        status:'belum',
                    };
                }
            });
        }
    } catch (error) {
        console.error('Error fetching user data:', error);
    }
};

// Send Data
const kirim = async () => {
    load.value = false;
    try {
        // Filter item yang baru saja dicentang
        if (matchedPernyataan.value.length > 0) {
            const newCheckedItems = matchedPernyataan.value.filter(standar => standar.status != 'sudah' && standar.check);

            // Buat array untuk menyimpan data yang akan dikirim
            const formData = newCheckedItems.map(standar => ({
                'idLPM': form.value.user,
                'id': standar.id
            }));
            // Kirim data menggunakan axios.post() dengan async/await
            await Promise.all(formData.map(async (formItem) => {
                await axios.post(`${authStore.url}/assign`, formItem, authStore.header);
            }));
            load.value = true;
            CheckPernyataan(form.value.user);
        } else {
            const newCheckedItems = tipeAudit.value.filter(standar => standar.check);
            // Buat array untuk menyimpan data yang akan dikirim
            const formData = newCheckedItems.map(standar => ({
                'idLPM': form.value.user,
                'id': standar.id
            }));
            // Kirim data menggunakan axios.post() dengan async/await
            await Promise.all(formData.map(async (formItem) => {
                await axios.post(`${authStore.url}/assign`, formItem,authStore.header);
            }));
            load.value = true;
            CheckPernyataan(form.value.user);
        }
        // Panggil CheckPernyataan untuk memperbarui data pernyataan setelah pengiriman data
        cek();

    } catch (error) {
        console.error("Error sending data:", error);
    }
};

// OnMounted Method
const cek = onMounted(async () => {
    try {
        // Fetch data tipeAudit dan userAuditor menggunakan Promise.all
        const [resTipe, resUser] = await Promise.all([
            axios.get(`${authStore.url}/tipeAudits`,authStore.header),
            axios.get(`${authStore.url}/userAuditor`, authStore.header)
        ]);
        // Update nilai tipeAudit dan users
        tipeAudit.value = resTipe.data;
        users.value = resUser.data;
        if(form.value.user !=null){
            CheckPernyataan(form.value.user);
        }
    } catch (error) {
        console.error("Error fetching data:", error);
    }
});

// Update title halaman
document.title = `Assign Auditor | ${authStore.name}`;

// Unchecklist Method
const Unchecklist = (id) => {
    try{
        if(confirm("Ingin Membatalkan Tipe Audit ini Untuk Di Assign??")){
            axios .post(`${authStore.url}/batalAssign`,{
                'id': id,
                'idLPM': form.value.user,
            },authStore.header)
            cek();
        }
    }catch (e) {
        console.log(e)
    }
}
</script>
