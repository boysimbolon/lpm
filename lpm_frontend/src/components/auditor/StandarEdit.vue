<template v-if="authStore.role === 'auditor' ">
    <div class="bg-gray-200 h-screen p-4 flex flex-col gap-4">
        <div class="bg-white w-full rounded-xl flex flex-col shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Edit Standar Form</h1>

                <!-- Back Button -->
                <button @click="goBack" class="flex items-center gap-2 bg-neutral-400 hover:bg-neutral-500 shadow-md text-white py-2 px-4 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                    Back
                </button>
            </div>
            <!-- Header End -->
        </div>
        <form @submit.prevent="updateStandar" class="flex gap-4 flex-col lg:flex-row">
            <div class="bg-white w-full lg:w-1/2 rounded-xl flex flex-col divide-y-2 shadow-md">
                <!-- Form Start -->
                <div class="py-4 flex flex-col gap-4 divide-y-2">
                    <div class="flex flex-col gap-4">
                        <!-- Jenis Standar -->
                        <div class="flex flex-col gap-1 px-4">
                            <label class="font-medium" for="jenis_standar">Jenis Standar</label>
                            <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                                </svg>
                                <input v-model="idParams.idParams.standar.jenis_standar" type="text" id="jenis_standar" name="jenis_standar" class="p-2 w-full focus:outline-none">
                            </div>
                        </div>
                    </div>
                    <div class="px-4 pt-4 w-full flex justify-between items-center">
                        <div class="flex gap-2">
                            <label class="font-medium" for="salin">Salin Pernyataan</label>
                            <input type="checkbox" id="salin" name="salin" v-model="isCopy">
                        </div>
                        <!-- Save Button -->
                        <button v-if="!isCopy" type="submit" class="bg-sky-500 text-white py-2 px-4 rounded shadow-md hover:bg-sky-700">Save</button>
                    </div>
                </div>
                <!-- Form End -->
            </div>
            <div v-if="isCopy" class="bg-white w-full h-fit lg:w-1/2 rounded-xl flex flex-col divide-y-2 shadow-md">
                <div class="py-4 flex flex-col gap-4 divide-y-2">
                    <!-- Tipe Audit -->
                    <div class="flex flex-col gap-1 px-4">
                        <label class="font-medium" for="tipeAudit">Salin Standar Ke Tipe Audit</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
                                <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/>
                                <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/>
                            </svg>
                            <select v-model="idParams.idParams.standar.idtipe" name="tipeAudit" id="tipeAudit" class="p-2 w-full focus:outline-none capitalize">
                                <option v-for="tipe in tipeAudit" :key="tipe.id" :value="tipe.id" class="capitalize">{{ tipe.tipe_audit }} - {{tipe.fakultas.prodi}} - {{ tipe.periode }} - {{ tipe.semester }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="px-4 pt-4 w-full flex justify-end">
                        <button type="submit" class="bg-sky-500 text-white py-2 px-4 rounded shadow-md hover:bg-sky-700">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
// Import Library
import axios from "axios";
import {ref, onMounted} from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/router/auth.js";

// Variable Declaration
const idParams = useAuthStore();
const authStore = useAuthStore();
const router = useRouter();
const tipeAudit = ref(null);
const isCopy = ref(false);
document.title = `Edit Standar | ${authStore.name}`;

// OnMounted Method
onMounted(async () => {
    console.log(authStore);
    if(authStore.role !== 'auditor'){
        alert("Password dan Username Tidak Tepat");
        router.push({ name: "login" });
    }
    try {
        const response = await axios.get(`${authStore.url}/myTipeAudit/${authStore.uid}`, authStore.header);
        tipeAudit.value = response.data;
    } catch (e) {
        console.log(e);
    }
});

// Update Method
const updateStandar = async () => {
    try {
        const id = idParams.idParams.standar.id;
        await axios.put(`${authStore.url}/standars/${id}`, {
            jenis_standar: idParams.idParams.standar.jenis_standar,
            id_tipe_audits: idParams.idParams.standar.idtipe
        },authStore.header);

        idParams.idParams.standar = null;

        if (idParams.idParams.standar === null) {
            const data = {
                id: idParams.idParams.id,
                tipe_audit: idParams.idParams.tipe_audit,
                periode: idParams.idParams.periode,
                semester: idParams.idParams.semester
            };
            authStore.scope(data);
            router.push({ name: 'StandarListAsAuditor' })
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            formErros.value = error.response.data.errors;
        } else {
            console.log(error);
        }
    }
}

// Back Method
const goBack = () => {
    const data = {
        id: idParams.idParams.id,
        tipe_audit: idParams.idParams.tipe_audit,
        periode: idParams.idParams.periode,
        semester: idParams.idParams.semester
    };
    authStore.scope(data);

    router.push({ name: 'StandarListAsAuditor' });
}
</script>
