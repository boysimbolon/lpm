<template v-if="store.role === 'auditor'">
    <div class="bg-gray-200 h-screen p-4">
        <div class="bg-white w-3/4 rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Edit Pernyataan Form</h1>

                <!-- Back Button -->
                <button @click="goBack" class="flex items-center gap-2 bg-neutral-400 hover:bg-neutral-500 shadow-md text-white py-2 px-4 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                    Back
                </button>
            </div>
            <!-- Header End -->

            <!-- Form Start -->
            <form @submit.prevent="save" class="py-4 flex flex-col gap-4 divide-y-2">
                <div class="flex flex-col gap-4">
                    <!-- Pernyataan -->
                    <div class="flex flex-col gap-1 px-4">
                        <label class="font-medium" for="nilai_diri">Pernyataan</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                                <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
                            </svg>
                            <input v-model="param.standar.pernyataan" type="text" class="p-2 w-full focus:outline-none" />
                        </div>
                    </div>

                    <!-- Maximum Score -->
                    <div class="flex flex-col gap-1 px-4">
                        <label class="font-medium" for="nilai_diri">Nilai Maksimum</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                                <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
                            </svg>
                            <input v-model="param.standar.poin" type="number" id="nilai_diri" name="nilai_diri" class="p-2 w-full focus:outline-none" />
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="px-4 pt-4 flex justify-between">
                    <button type="submit" class="bg-sky-500 text-white py-2 px-4 rounded w-fit shadow-md">Save</button>
                    <div class="bg-red-500 text-white py-2 px-4 rounded w-fit shadow-md" @click="Delete">Delete</div>
                </div>
            </form>
            <!-- Form End -->
        </div>
    </div>
</template>

<script setup>
// Import Library
import { useAuthStore } from "@/router/auth.js";
import axios from "axios";
import router from "@/router/index.js";

// Variable Declaration
const authStore = useAuthStore();
const store = useAuthStore();
const param = store.idParams;
document.title = `Edit Pernyataan Standar | ${authStore.name}`;

// Save Method
const save =  () => {
    axios.put(`${authStore.url}/per-standars/${param.standar.id}`,
        {
            pernyataan_standar: param.standar.pernyataan,
            poin: param.standar.poin
        }, authStore.header)
        .then(router.push({name: 'CreatePernyataanStandarAsAuditor'}));
};

// Delete Method
const Delete = async () => {
    const input = window.prompt("Masukkan kode verify:");

    if (input !== null) {
        try {
            const response = await axios.post(`${authStore.url}/verify`, {code: input}, {
                headers: {
                    Authorization: `Bearer ${store.accessToken}`,
                },
            });

            if (response.data.valid) {
                if (confirm("Ingin menghapus statement dan nilai?")) {
                    await axios.delete(`${authStore.url}/per-standars/${param.standar.id}`, authStore.header);

                    const data = {
                        tipeAuditParam: param.tipeAuditParam,
                        standarParam: param.standarParam
                    }
                    store.scope(data);

                    router.push({name: 'CreatePernyataanStandarAsAuditor'});
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

// Back Method
const goBack = () => {
    const data = {
        tipeAuditParam: param.tipeAuditParam,
        standarParam: param.standarParam
    }
    store.scope(data);
    router.push({name: 'CreatePernyataanStandarAsAuditor'});
};
</script>
