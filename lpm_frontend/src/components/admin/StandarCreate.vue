<template v-if="authStore.role === 'admin'">
    <div class="bg-gray-200 min-h-screen p-4">
        <div class="bg-white w-3/4 rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Standar Form</h1>

                <!-- Back Button -->
                <button @click="goBack" class="flex items-center gap-2 bg-neutral-400 hover:bg-neutral-500 shadow-md text-white p-2 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                    Back
                </button>
            </div>
            <!-- Header Start -->

            <!-- Form Start -->
            <form @submit.prevent="createStandar" class="py-4 flex flex-col gap-4 divide-y-2">
                <div class="flex flex-col gap-4">
                    <!-- Jenis Standar -->
                    <div class="flex flex-col gap-1 px-4">
                        <label class="font-medium" for="jenis_standar">Jenis Standar</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                                <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
                            </svg>
                            <input v-model="form.jenis_standar" type="text" placeholder="Jenis Standar" id="jenis_standar" name="jenis_standar" class="p-2 w-full focus:outline-none">
                        </div>
                    </div>

                    <!-- Tipe Audit -->
                    <div class="flex flex-col gap-1 px-4">
                        <label class="font-medium" for="tipeAudit">Tipe Audit</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
                                <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/>
                                <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/>
                            </svg>
                            <select v-model="form.idtipe" name="tipeAudit" id="tipeAudit" class="p-2 w-full focus:outline-none capitalize">
                                <option v-for="tipe in tb_tipeAudit" :key="tipe.id" :value="tipe.id" class="capitalize">{{ tipe.tipe_audit }} - {{ tipe.periode }} - {{ tipe.semester }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="px-4 pt-4 w-full flex flex-row-reverse">
                    <button type="submit" class="bg-sky-500 text-white py-2 px-4 rounded hover:bg-sky-700 shadow-md">Save</button>
                </div>
            </form>
            <!-- Form End -->
        </div>
    </div>
</template>

<script setup>
// Import Library
import axios from "axios";
import { onMounted, ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useAuthStore } from "@/router/auth.js";

// Variable Declaration
const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();
const idParams = route.params.id;
const form = ref({
    jenis_standar: null,
    idtipe: null
});
const tb_tipeAudit = ref([]);
document.title = `Create Standar | ${authStore.name}`;

// onMounted Method
onMounted(async () => {
    if(authStore.role !== 'admin'){
        alert("Password dan Username Tidak Tepat");
        router.push({ name: "login" });
    }
    if(authStore.role === 'admin'){
        const resTipe = await axios.get(`${authStore.url}/tipeAudits`,authStore.header);
        tb_tipeAudit.value = resTipe.data;
    }
});

// Create Method
const createStandar = async () => {
    try {
        await axios.post(`${authStore.url}/standars`, form.value,authStore.header);

        router.push({ name: 'StandarListAsAdmin' });
    } catch (e) {
        console.log(e);
    }
}

// Back Method
const goBack = () => {
    router.push({ name: 'StandarListAsAdmin', params: { id: idParams } });
}
</script>
