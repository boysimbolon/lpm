<template v-if="authStore.role === 'admin'">
    <div class="bg-gray-200 min-h-screen p-4">
        <div class="bg-white rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Tipe Audit - Standar</h1>
            </div>
            <!-- Header End -->

            <!-- Table Start -->
            <div class="overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="overflow-auto h-fit">
                        <table class="table-auto text-left w-full">
                            <thead>
                                <tr>
                                    <th class="p-4 w-[5%] text-center">No.</th>
                                    <th class="p-4 w-[25%]">Tipe Audit</th>
                                    <th class="p-4 w-[20%]">Fakultas</th>
                                    <th class="p-4 w-[20%]">Prodi</th>
                                    <th class="p-4 w-[10%] text-center">Tahun Ajar</th>
                                    <th class="p-4 w-[10%] text-center">Semester</th>
                                    <th class="p-4 w-[10%] text-center">View Standar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(tipe_audit, index) in tb_tipe_audit" :key="tipe_audit.id" class="border-y">
                                    <td class="p-4 w-[5%] text-center">{{ index + 1 }}</td>
                                    <td class="p-4 w-[25%]">{{ tipe_audit.tipe_audit }}</td>
                                    <template v-if="tipe_audit.fakultas !=null">
                                        <td class="p-4 w-[20%]">{{ tipe_audit.fakultas.fakultas }}</td>
                                        <td class="p-4 w-[20%]">{{ tipe_audit.fakultas.prodi }}</td>
                                    </template>
                                    <template v-else>
                                        <td class="p-4 w-[20%]">-</td>
                                        <td class="p-4 w-[20%]">-</td>
                                    </template>
                                    <td class="p-4 w-[10%] text-center">{{ tipe_audit.periode }}</td>
                                    <td class="p-4 w-[10%] text-center capitalize">{{ tipe_audit.semester }}</td>
                                    <td class="p-4 flex flex-col lg:flex-row items-center gap-4 justify-center">
                                        <router-link :to="{ name: 'StandarListAsAuditor' }" @click="view(tipe_audit)" class="py-2 px-4 text-white rounded bg-blue-500 hover:bg-blue-700 shadow-md">View</router-link>
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
const tb_tipe_audit = ref([]);
const authStore = useAuthStore();

// OnMounted Method
onMounted(async () => {
    if(authStore.role !== 'auditor'){
        alert("Password dan Username Tidak Tepat");
        router.push({ name: "login" });
    }
    const response = await axios.get(`${authStore.url}/myTipeAudit/${authStore.uid}`, authStore.header);
    tb_tipe_audit.value = response.data;
});

// View Method
const view = (id) => {
    idParams.scope(id);
    console.log(id);
}
</script>
