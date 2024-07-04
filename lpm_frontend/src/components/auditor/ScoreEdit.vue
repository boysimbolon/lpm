<template v-if="store.role === 'auditor'">
    <div class="bg-gray-200 min-h-screen p-4">
        <div class="bg-white w-3/4 rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Edit Audit Form</h1>

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
                    <!-- Pernyataan Standar -->
                    <div class="flex flex-col gap-1 px-4">
                        <label class="font-semibold" for="jenis_standar">Pernyataan</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                                <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
                            </svg>
                            <input class="p-2 w-full focus:outline-none" v-model="param.standar.per_standars.pernyataan_standar">
                        </div>
                    </div>

                    <!-- Nilai Diri -->
                    <div class="flex flex-col gap-1 px-4">
                        <label class="font-semibold" for="nilai_diri">Nilai Diri</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                                <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
                            </svg>
                            <input v-model="param.standar.nilai_diri" step="0.01" type="number" id="nilai_diri" name="nilai_diri" class="p-2 w-full focus:outline-none" :min="1" required/>
                        </div>
                        <span class="text-red-400"> *maksimum: {{ param.standar.per_standars.poin }}</span>
                    </div>

                    <!-- Feedback -->
                    <div class="flex flex-col gap-1 px-4">
                        <label class="font-semibold" for="jenis_standar">Edit Feedback</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-chat-left" viewBox="0 0 16 16">
                                <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                            </svg>
                            <input type="text" class="p-2 w-full focus:outline-none" v-model="param.standar.feedback ">
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="px-4 pt-4 w-full flex justify-end">
                    <button type="submit" class="bg-sky-500 text-white py-2 px-4 rounded hover:bg-sky-700 shadow-md">Save</button>
                </div>
            </form>
            <!-- Form End -->
        </div>
    </div>
</template>

<script setup>
import { useAuthStore } from "@/router/auth.js";
import axios from "axios";
import router from "@/router/index.js";
import {convertStringToNumberByType} from "jsdom/lib/jsdom/living/helpers/number-and-date-inputs.js";

const store = useAuthStore();
const param = store.idParams;

// Save Method
const save = async () => {
    // Validate nilai_diri
    let data = new FormData();
    if (param.standar.nilai_diri != 0) {
        data.append('nilai_diri', param.standar.nilai_diri);
    }
    if (param.standar.nilai_diri != null) {
        data.append('feedback', param.standar.feedback);
    }
    try {
        const parameter = {
            tipeAuditParam: store.idParams.tipeAuditParam,
            standarParam: store.idParams.standarParam
        }
        store.scope(parameter);

        await axios.post(`${store.url}/audits/${param.standar.id}`, data,store.header);
        await router.push({ name: 'GradingAsAuditor' });
    } catch (error) {
        console.error('Error:', error);
    }
};

// Back Method
const goBack = () => {
    const data = {
        tipeAuditParam: store.idParams.tipeAuditParam,
        standarParam: store.idParams.standarParam
    }
    store.scope(data);
    router.push({ name: 'GradingAsAuditor' });
};
</script>
