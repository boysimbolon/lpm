<template v-if="authStore.role === 'auditor'">
    <div class="bg-gray-200 min-h-screen p-4">
        <div class="bg-white w-3/4 rounded-xl flex flex-col divide-y-2 shadow-md">
            <!-- Header Start -->
            <div class="p-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold">Edit User Form</h1>

                <!-- Back Button -->
                <button @click="goBack" class="flex items-center gap-2 bg-neutral-400 hover:bg-neutral-500 shadow-md text-white py-2 px-4 rounded shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                    Back
                </button>
            </div>
            <!-- Header End -->

            <!-- Form Start -->
            <form @submit.prevent="updateUser" class="py-4 flex flex-col gap-4 divide-y-2">
                <div class="flex flex-col gap-4 px-4">
                    <!-- Username -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium" for="username">Username</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                            </svg>
                            <input v-model="idParams.idParams.username" type="text" placeholder="Username" id="username" name="username" class="p-2 w-full focus:outline-none">
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium" for="nama">Nama</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                            </svg>
                            <input v-model="idParams.idParams.name" type="text" placeholder="Name" id="nama" name="nama" class="p-2 w-full focus:outline-none">
                        </div>
                    </div>

                    <!-- Fakultas -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium" for="fakultas">Fakultas</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-mortarboard" viewBox="0 0 16 16">
                                <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917zM8 8.46 1.758 5.965 8 3.052l6.242 2.913z"/>
                                <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466zm-.068 1.873.22-.748 3.496 1.311a.5.5 0 0 0 .352 0l3.496-1.311.22.748L8 12.46z"/>
                            </svg>
                            <select v-model="idParams.idParams.id_fakultas" name="fakultas" id="fakultas" class="p-2 w-full focus:outline-none">
                                <option v-for="fakultas in tb_fakultas" :key="fakultas.id" :value="fakultas.id">{{ fakultas.fakultas }} - {{ fakultas.prodi}}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium" for="role">Role</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-badge" viewBox="0 0 16 16">
                                <path d="M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                <path d="M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5v10.795a4.2 4.2 0 0 0-.776-.492C11.392 12.387 10.063 12 8 12s-3.392.387-4.224.803a4.2 4.2 0 0 0-.776.492z"/>
                            </svg>
                            <select v-model="idParams.idParams.id_roles" name="role" id="role" class="p-2 w-full focus:outline-none">
                                <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.jenis }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium" for="password">Password</label>
                        <div class="flex items-center divide-x-[1px] divide-black gap-2 pl-2 border border-black rounded bg-neutral-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1"/>
                            </svg>
                            <input v-model="idParams.idParams.password" type="password" placeholder="Password" id="password" name="password" class="p-2 w-full focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="px-4 pt-4">
                    <button type="submit" class="bg-sky-500 text-white py-2 px-4 rounded shadow-md">Save</button>
                </div>
            </form>
            <!-- Form End -->
        </div>
    </div>
</template>

<script setup>
// Import Library
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/router/auth.js";

// Variable Declaration
const idParams = useAuthStore();
const router = useRouter();
const authStore = useAuthStore();
const tb_fakultas = ref([]);
const roles = ref([]);

// onMounted Method
onMounted(async () => {
    if (authStore.role != 'auditor') {
        alert("Password dan Username Tidak Tepat");
        router.push({ name: "login" });
    }
    try {
        const [fakultasResponse, rolesResponse] = await Promise.all([
            axios.get(`${authStore.url}/fakultas`, authStore.header),
            axios.get(`${authStore.url}/roles`, authStore.header)
        ]);

        tb_fakultas.value = fakultasResponse.data;
        roles.value = rolesResponse.data.filter(res => res.jenis == 'auditee');
    } catch (e) {
        console.log(e)
    }
});

// Update Method
const updateUser = async () => {
    try {
        await axios.put(`${authStore.url}/users/${idParams.idParams.id}`,{
            username: idParams.idParams.username,
            name: idParams.idParams.name,
            id_fakultas: idParams.idParams.id_fakultas,
            id_role: idParams.idParams.id_roles,
            password: idParams.idParams.password
        }, authStore.header);

        idParams.idParams = null;
        router.push({ name: 'UserListAsAuditor' });
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
    router.push({ name: "UserListAsAuditor" });
}
</script>
