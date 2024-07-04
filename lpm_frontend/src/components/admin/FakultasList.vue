<template v-if="authStore.role === 'admin'">
    <div class="bg-gray-200 min-h-screen p-4">
        <div class="bg-white w-full h-full rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Fakultas Table</h1>

                <!-- Create Button -->
                <router-link :to="{ name: 'CreateFakultasAsAdmin' }" class="flex items-center bg-sky-500 text-white py-2 px-4 rounded hover:bg-sky-700 shadow-md" >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                    Create
                </router-link>
            </div>
            <!-- Header End -->

            <!-- Table Start -->
            <div class="overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="overflow-auto h-full">
                        <table class="table-auto text-left w-full">
                            <thead>
                                <tr class="flex">
                                    <th class="p-4 basis-[5%] text-center">No.</th>
                                    <th class="p-4 basis-[50%]">Fakultas</th>
                                    <th class="p-4 basis-[50%]">Prodi</th>
                                    <th class="p-4 basis-[50%]">Auditor</th>
                                    <th class="p-4 basis-[15%] text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(fakultas, index) in tb_fakultas" :key="fakultas.id" class="border-y flex items-center">
                                    <td class="p-4 basis-[5%] text-center">{{ index + 1 }}</td>
                                    <td class="p-4 basis-[50%]">{{ fakultas.fakultas }}</td>
                                    <td class="p-4 basis-[50%]">{{ fakultas.prodi }}</td>
                                    <td class="p-4 basis-[50%]">
                                        <tr v-for="user in tb_users" :key="user.id" class="border-y flex items-center">
                                            <td class="p-4" v-if="user.id_fakultas == fakultas.id">{{ user.name }}</td>
                                        </tr>
                                    </td>
                                    <td class="p-4 flex items-center gap-4 basis-[15%] justify-center">
                                        <!-- Edit Button -->
                                        <router-link :to="{ name: 'UpdateFakultasAsAdmin' }" class="p-1
                                           rounded border-2 border-blue-500 bg-blue-500" @click="edit(fakultas)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                            </svg>
                                        </router-link>

                                        <!-- Delete Button -->
                                        <button @click="deleteFakultas(fakultas)" class="p-1 rounded border-2 border-red-500 bg-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table End -->
        </div>
    </div>
</template>

<script setup>
// Import Library
import axios from "axios";
import { ref, onMounted } from "vue";
import router from "@/router/index.js";
import { useAuthStore } from "@/router/auth.js";

// Variable Declaration
const tb_fakultas = ref([]);
const tb_users = ref([]);
const authStore = useAuthStore();
const idParams = useAuthStore();
document.title = `Fakultas List | ${authStore.name}`;

// OnMounted Method
onMounted(async () => {
    if(authStore.role !== 'admin'){
        alert("Password dan Username Tidak Tepat");
        router.push({ name: "login" });
    }
    const resFakultas = await axios.get(`${authStore.url}/fakultas`,authStore.header);
    const resUser = await axios.get(`${authStore.url}/users`,authStore.header)
    if(authStore.role !== 'admin'){
        tb_fakultas.value = resFakultas.data.filter(res => res.fakultas !== 'Universitas Advent Indonesia');
    } else {
        tb_fakultas.value = resFakultas.data;
    }

    tb_users.value = resUser.data;
});

// Delete Method
const deleteFakultas = async (fakultas) => {
    const input = window.prompt("Masukkan kode verify:");

    if (input !== null) {
        try {
            const response = await axios.post(`${authStore.url}/verify`,
                { code: input }, authStore.header);

            if (response.data.valid) {
                if (confirm("Are you sure you want to delete this fakultas?")) {
                    await axios.delete(`${authStore.url}/fakultas/${fakultas.id}`,authStore.header);
                    const res = await axios.get(`${authStore.url}/fakultas`,authStore.header);
                    tb_fakultas.value = res.data;
                    router.push({ name: 'FakultasListAsAdmin' });
                }
            } else {
                alert("Input tidak valid. Harap masukkan kode verify yang benar.");
            }
        } catch (error) {
            console.error('Error during verification:', error);
            alert("Terjadi kesalahan saat verifikasi. Harap coba lagi.");
        }
    }
}

// Edit Method
const edit = (data) => {
    idParams.scope(data);
}
</script>
