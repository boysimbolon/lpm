<template v-if="authStore.role === 'admin'">
    <div class="bg-gray-200 min-h-screen p-4">
        <div class="bg-white rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold capitalize">{{ idParams.idParams.tipe_audit }} - {{ idParams.idParams.periode }} - {{ idParams.idParams.semester }}</h1>
                <div class="flex gap-4 items-center">
                    <!-- Create Button -->
                    <router-link :to="{ name: 'CreateStandarAsAdmin', id: idParams.idParams.id }" class="flex items-center bg-sky-500 text-white py-2 px-4 rounded hover:bg-sky-700 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                        </svg>
                        Create
                    </router-link>

                    <!-- Back Button -->
                    <button @click="goBack" class="flex items-center gap-2 bg-neutral-400 hover:bg-neutral-500 shadow-md text-white py-2 px-4 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                        </svg>
                        Back
                    </button>
                </div>
            </div>
            <!-- Header End -->

            <!-- Table Start -->
            <div class="overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="overflow-auto h-full">
                        <table class="table-auto text-left w-full">
                            <thead>
                                <tr>
                                    <th class="p-4 w-[5%] text-center">No.</th>
                                    <th class="p-4 w-[45%]">Jenis Standar</th>
                                    <th class="p-4 w-[30%] text-center">Status</th>
                                    <th class="p-4 w-[10%] text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(standar,index) in tb_standar" :key="standar.id" class="border-y">
                                    <td class="p-4 w-[5%] text-center">{{ index + 1 }}</td>
                                    <td class="p-4 w-[45%]">{{ standar.jenis_standar }}</td>
                                    <td class="p-4 w-[30%] text-white">
                                        <div class="flex items-center justify-center">
                                            <div class="flex gap-4 w-fit py-2 px-4 rounded bg-green-500 items-center" v-if="standar.status === true">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-lock" viewBox="0 0 16 16">
                                                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1"/>
                                                </svg>
                                                <span>Terverifikasi</span>
                                            </div >
                                            <div class="flex gap-4 w-fit py-2 px-4 rounded bg-blue-500 items-center" v-else-if="standar.status === false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-unlock" viewBox="0 0 16 16">
                                                    <path d="M11 1a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h5V3a3 3 0 0 1 6 0v4a.5.5 0 0 1-1 0V3a2 2 0 0 0-2-2M3 8a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1z"/>
                                                </svg>
                                                <span>Belum Terverifikasi</span>
                                            </div>
                                            <div class="flex gap-4 w-fit py-2 px-4 rounded bg-red-500 items-center" v-else>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-ban" viewBox="0 0 16 16">
                                                    <path d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0"/>
                                                </svg>
                                                <span>Belum Di Assign</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 flex flex-col lg:flex-row items-center justify-center gap-4">
                                        <!-- Edit Button -->
                                        <router-link :to="{ name: 'UpdateStandarAsAdmin', params: { id: idParams.idParams.id, id_tipe_audit: idParams.idParams.id_tipe_audit } }" @click="edit(standar, idParams.idParams.id, idParams.idParams.tipe_audit, idParams.idParams.periode, idParams.idParams.semester)" class="p-1 rounded border-2 border-blue-500 bg-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                 height="24" fill="white" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                            </svg>
                                        </router-link>

                                        <!-- Delete Button -->
                                        <button @click="deleteStandar(standar)" class="p-1 rounded border-2 border-red-500 bg-red-500">
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
const idParams = useAuthStore();
const tb_standar = ref([]);
const authStore = useAuthStore();
document.title = `Standar List | ${authStore.name}`;

// onMounted Method
const request = onMounted(async () => {
    console.log(authStore)

    if(authStore.role === 'admin'){
        const response = await axios.get(`${authStore.url}/Standars/${idParams.idParams.id}`,authStore.header);
        tb_standar.value = response.data;
    }
});

// Delete Method
const deleteStandar = async (standar) => {
    const input = window.prompt("Masukkan kode verify:");
    if (input !== null) {
        const response = await axios.post(`${authStore.url}/verify`,
            { code: input }, authStore.header);
        if (response.data.valid) {
            if (confirm("Tetap Yakin Untuk Menghapus Standar ini?")) {
                await axios.delete(`${authStore.url}/standars/${standar.id}`, authStore.header);
                request();
            }

            router.push({ name: 'StandarListAsAdmin' })
        } else {
            alert("Input tidak valid. Harap masukkan kode verify yang benar.");
        }
    }
}

// Edit Method
const edit = (standar, idTipeAudit, tipeAuditParam, periodeParam, semesterParam) => {
    const data = {
        standar: standar,
        id: idTipeAudit,
        tipe_audit: tipeAuditParam,
        periode: periodeParam,
        semester: semesterParam
    }
    idParams.scope(data);
}

// Back Method
const goBack = () => {
    router.push({ name: 'StandarViewAsAdmin' });
}
</script>
