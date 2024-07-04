<template>
    <div class="flex h-full font-['Rubik']">
        <!-- Sidebar Start -->
        <aside v-if="!isLoginPage && authStore.isLoggedIn" :style="{ width: sidebarWidth, minWidth: sidebarWidth }" class="sticky top-0 bottom-0 min-w-16 max-w-64 overflow-x-hidden h-screen flex flex-col items-start justify-between transition-all duration-700 ease-in-out">
            <div class="w-full flex flex-col items-start">
                <!-- Hamburger Menu -->
                <div class="flex items-center gap-6 pl-[18px] cursor-pointer py-3">
                    <svg @click="toggleSidebar" xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                    </svg>
                    <div class="text-md text-nowrap">LPM UNAI</div>
                </div>

                <!-- Dashboard Audite -->
                <RouterLink :to="{ name: 'Audite' }" :style="{ width: sidebarWidth, minWidth: sidebarWidth }" :class="{ 'border-l-4 border-blue-500 pl-[15px] bg-neutral-300' :$route.name === 'Audite' }" class="flex items-center gap-6 pl-5 py-3 text-nowrap transition-all duration-700 ease-in-out" v-if="userRole == 'auditee'">
                    <div class="w-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-grid" viewBox="0 0 16 16">
                            <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/>
                        </svg>
                    </div>
                    <p class="text-md">Dashboard</p>
                </RouterLink>

                <!-- Dashboard Auditor -->
                <RouterLink :to="{ name: 'LPM' }" :style="{ width: sidebarWidth, minWidth: sidebarWidth }" :class="{ 'border-l-4 border-blue-500 pl-[15px] bg-neutral-300' :$route.name === 'LPM' }" class="flex items-center gap-6 pl-5 py-3 text-nowrap transition-all duration-700 ease-in-out" v-if="userRole == 'auditor'">
                    <div class="w-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-grid" viewBox="0 0 16 16">
                            <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/>
                        </svg>
                    </div>
                    <p class="text-md">Dashboard</p>
                </RouterLink>

                <!-- Dashboard Admin -->
                <RouterLink :to="{ name: 'Admin' }" :style="{ width: sidebarWidth, minWidth: sidebarWidth }" :class="{ 'border-l-4 border-blue-500 pl-[15px] bg-neutral-300' :$route.name === 'Admin' }" class="flex items-center gap-6 pl-5 py-3 text-nowrap transition-all duration-700 ease-in-out" v-if="userRole == 'admin'">
                    <div class="w-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-grid" viewBox="0 0 16 16">
                            <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/>
                        </svg>
                    </div>
                    <p class="text-md">Dashboard</p>
                </RouterLink>

                <!-- Create Audit as Auditor -->
                <RouterLink v-if="userRole === 'auditor'" :to="{ name: 'AssignAuditeAsAuditor' }" :style="{ width: sidebarWidth, minWidth: sidebarWidth }" :class="{ 'border-l-4 border-blue-500 pl-[15px] bg-neutral-300' :$route.name === 'AssignAuditeAsAuditor' }" class="flex items-center gap-6 pl-5 py-3 text-nowrap transition-all duration-700 ease-in-out">
                    <div class="w-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-table" viewBox="0 0 16 16">-->
                            <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 2h-4v3h4zm0 4h-4v3h4zm0 4h-4v3h3a1 1 0 0 0 1-1zm-5 3v-3H6v3zm-5 0v-3H1v2a1 1 0 0 0 1 1zm-4-4h4V8H1zm0-4h4V4H1zm5-3v3h4V4zm4 4H6v3h4z"/>
                        </svg>
                    </div>
                    <p class="text-md">Assign Auditee</p>
                </RouterLink>

                <!-- Grading as Auditor -->
                <RouterLink v-if="userRole === 'auditor'" :to="{ name: 'GradingAsAuditor' }" :style="{ width: sidebarWidth, minWidth: sidebarWidth }" :class="{ 'border-l-4 border-blue-500 pl-[15px] bg-neutral-300' :$route.name === 'GradingAsAuditor' }"
                            class="flex items-center gap-6 pl-5 py-3 text-nowrap transition-all duration-700 ease-in-out">
                    <div class="w-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-check-square" viewBox="0 0 16 16">
                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                            <path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z"/>
                        </svg>
                    </div>
                    <p class="text-md">Input Nilai Diri</p>
                </RouterLink>

                <!-- Assign Auditor as Admin -->
                <RouterLink v-if="userRole === 'admin'" :to="{ name: 'AssignAuditorAsAdmin' }" :style="{ width: sidebarWidth, minWidth: sidebarWidth }" :class="{ 'border-l-4 border-blue-500 pl-[15px] bg-neutral-300':$route.name === 'AssignAuditorAsAdmin' }" class="flex items-center gap-6 pl-5 py-3 text-nowrap transition-all duration-700 ease-in-out">
                    <div class="w-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                        </svg>
                    </div>
                    <p class="text-md">Assign Auditor</p>
                </RouterLink>

                <!-- Create Audite as Admin -->
                <RouterLink v-if="userRole === 'admin'" :to="{ name: 'AssignAuditeAsAdmin' }" :style="{ width: sidebarWidth, minWidth: sidebarWidth }" :class="{ 'border-l-4 border-blue-500 pl-[15px] bg-neutral-300':$route.name === 'AssignAuditeAsAdmin' }" class="flex items-center gap-6 pl-5 py-3 text-nowrap transition-all duration-700 ease-in-out">
                    <div class="w-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-table" viewBox="0 0 16 16">-->
                            <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 2h-4v3h4zm0 4h-4v3h4zm0 4h-4v3h3a1 1 0 0 0 1-1zm-5 3v-3H6v3zm-5 0v-3H1v2a1 1 0 0 0 1 1zm-4-4h4V8H1zm0-4h4V4H1zm5-3v3h4V4zm4 4H6v3h4z"/>
                        </svg>
                    </div>
                    <p class="text-md">Assign Auditee</p>
                </RouterLink>

                <!-- Setting Menu as Admin -->
                <div v-if="userRole === 'admin'" :style="{ width: sidebarWidth, minWidth: sidebarWidth }" :class="{ 'border-l-4 border-blue-500 pl-0 py-3 bg-neutral-300'
                                :$route.name === 'UserListAsAdmin' ||
                                $route.name === 'CreateUserAsAdmin' ||
                                $route.name === 'UpdateUserAsAdmin' ||
                                $route.name === 'StandarViewAsAdmin' ||
                                $route.name === 'StandarListAsAdmin' ||
                                $route.name === 'CreateStandarAsAdmin' ||
                                $route.name === 'UpdateStandarAsAdmin' ||
                                $route.name === 'CreatePernyataanStandarAsAdmin' ||
                                $route.name === 'EditPernyataanStandarAsAdmin' ||
                                $route.name === 'TipeAuditListAsAdmin' ||
                                $route.name === 'CreateTipeAuditAsAdmin' ||
                                $route.name === 'UpdateTipeAuditAsAdmin' ||
                                $route.name === 'FakultasListAsAdmin' ||
                                $route.name === 'CreateFakultasAsAdmin' ||
                                $route.name === 'UpdateFakultasAsAdmin' }" class="flex items-center gap-6 pl-1 pr-5 py-3 text-nowrap cursor-pointer transition-all duration-700 ease-in-out">
                    <div @click="handleClick" class="pl-[15px]">
                        <div class="w-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center justify-between w-full">
                        <p class="text-md">Pengaturan</p>
                        <svg @click="toggleDropdown" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Drop Down Menu as Admin -->
                <div v-if="userRole === 'admin' && dropdownVisible"
                     class="w-full transition duration-700 ease-in-out text-nowrap">
                    <RouterLink :to="{ name:'UserListAsAdmin' }" class="block pl-[68px] py-2" :class="{ 'py-3 bg-neutral-300':
                                $route.name === 'UserListAsAdmin' ||
                                $route.name === 'CreateUserAsAdmin' ||
                                $route.name === 'UpdateUserAsAdmin' }">User</RouterLink>
                    <RouterLink :to="{ name:'StandarViewAsAdmin' }" class="block pl-[68px] py-2" :class="{ 'py-3 bg-neutral-300':
                                $route.name === 'StandarViewAsAdmin' ||
                                $route.name === 'StandarListAsAdmin' ||
                                $route.name === 'CreateStandarAsAdmin' ||
                                $route.name === 'UpdateStandarAsAdmin' }">Standar</RouterLink>
                    <RouterLink :to="{ name: 'CreatePernyataanStandarAsAdmin' }" class="block pl-[68px] py-2" :class="{ 'py-3 bg-neutral-300':
                                $route.name === 'CreatePernyataanStandarAsAdmin' ||
                                $route.name === 'EditPernyataanStandarAsAdmin' }">Pernyataan Standar</RouterLink>
                    <RouterLink :to="{ name: 'FakultasListAsAdmin' }" class="block pl-[68px] py-2"
                                :class="{ 'py-3 bg-neutral-300':
                                $route.name === 'FakultasListAsAdmin' ||
                                $route.name === 'CreateFakultasAsAdmin' ||
                                $route.name === 'UpdateFakultasAsAdmin' }">Fakultas</RouterLink>
                    <RouterLink :to="{ name: 'TipeAuditListAsAdmin' }" class="block pl-[68px] py-2" :class="{ 'py-3 bg-neutral-300':
                                $route.name === 'TipeAuditListAsAdmin' ||
                                $route.name === 'CreateTipeAuditAsAdmin' ||
                                $route.name === 'UpdateTipeAuditAsAdmin' }">Tipe Audit</RouterLink>
                </div>

                <!-- Audit Menu as Audite -->
                <div v-else-if="userRole === 'auditee'" :style="{ width: sidebarWidth, minWidth: sidebarWidth }" :class="{ 'border-l-4 border-blue-500 pl-0 py-3 bg-neutral-300' :$route.name === 'IsiDokumenAudit' || $route.name === 'EditFile' || $route.name === 'FeedbackAuditee' || $route.name === 'UpdateMe' }" class="flex items-center gap-6 pl-1 pr-5 py-3 text-nowrap cursor-pointer transition-all duration-700 ease-in-out">
                    <div @click="handleClick" class="pl-[15px]">
                        <div class="w-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-table" viewBox="0 0 16 16">
                                <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 2h-4v3h4zm0 4h-4v3h4zm0 4h-4v3h3a1 1 0 0 0 1-1zm-5 3v-3H6v3zm-5 0v-3H1v2a1 1 0 0 0 1 1zm-4-4h4V8H1zm0-4h4V4H1zm5-3v3h4V4zm4 4H6v3h4z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center justify-between w-full">
                        <p class="text-md">Audite</p>
                        <svg @click="toggleDropdown" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Drop Down Menu as Audite -->
                <div v-if="userRole === 'auditee' && dropdownVisible" class="w-full transition duration-700 ease-in-out text-nowrap">
                    <RouterLink :to="{ name: 'IsiDokumenAudit' }" class="block pl-[68px] py-2" :class="{ 'py-3 bg-neutral-300' :$route.name === 'IsiDokumenAudit' || $route.name === 'EditFile' }">Isi Audit</RouterLink>
                    <RouterLink :to="{ name: 'FeedbackAuditee' }" class="block pl-[68px] py-2" :class="{ 'py-3 bg-neutral-300' :$route.name === 'FeedbackAuditee' }">FeedBack Auditor</RouterLink>
                    <RouterLink :to="{ name: 'UpdateMe' }" class="block pl-[68px] py-2" :class="{ 'py-3 bg-neutral-300' :$route.name === 'UpdateMe' }">Edit Profile</RouterLink>
                </div>

                <!-- Setting Menu as Auditor -->
                <div v-else-if="userRole === 'auditor'" :style="{ width: sidebarWidth, minWidth: sidebarWidth }" :class="{ 'border-l-4 border-blue-500 pl-0 py-3 bg-neutral-300':
                                $route.name === 'UserListAsAuditor' ||
                                $route.name === 'CreateUserAsAuditor' ||
                                $route.name === 'UpdateUserAsAuditor' ||
                                $route.name === 'StandarListAsAuditor' ||
                                $route.name === 'StandarViewAsAuditor' ||
                                $route.name === 'CreateStandarAsAuditor' ||
                                $route.name === 'UpdateStandarAsAuditor'||
                                $route.name === 'CreatePernyataanStandarAsAuditor' ||
                                $route.name === 'UpdatePernyataanStandarAsAuditor' ||
                                $route.name === 'FakultasListAsAuditor' ||
                                $route.name === 'CreateFakultasAsAuditor' ||
                                $route.name === 'UpdateFakultasAsAuditor' ||
                                $route.name === 'TipeAuditListAsAuditor' ||
                                $route.name === 'CreateTipeAuditAsAuditor' ||
                                $route.name === 'UpdateTipeAuditAsAuditor' }" class="flex items-center gap-6 pl-1 pr-5 py-3 text-nowrap cursor-pointer transition-all duration-700 ease-in-out">
                    <div @click="handleClick" class="pl-[15px]">
                        <div class="w-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center justify-between w-full">
                        <p class="text-md">Pengaturan</p>
                        <svg @click="toggleDropdown" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Drop Down Menu as Auditor -->
                <div v-if="userRole === 'auditor' && dropdownVisible" class="w-full transition duration-700 ease-in-out text-nowrap">
                    <RouterLink :to="{ name: 'UserListAsAuditor' }" class="block pl-[68px] py-2" :class="{ 'py-3 bg-neutral-300':
                                $route.name === 'UserListAsAuditor' ||
                                $route.name === 'CreateUserAsAuditor' ||
                                $route.name === 'UpdateUserAsAuditor' }">User</RouterLink>
                    <RouterLink :to="{ name: 'StandarViewAsAuditor' }" class="block pl-[68px] py-2" :class="{ 'py-3 bg-neutral-300':
                                $route.name === 'StandarListAsAuditor' ||
                                $route.name === 'StandarViewAsAuditor' ||
                                $route.name === 'CreateStandarAsAuditor' ||
                                $route.name === 'UpdateStandarAsAuditor' }">Standar</RouterLink>
                    <RouterLink :to="{ name: 'CreatePernyataanStandarAsAuditor' }" class="block pl-[68px] py-2" :class="{ 'py-3 bg-neutral-300':
                                $route.name === 'CreatePernyataanStandarAsAuditor' ||
                                $route.name === 'UpdatePernyataanStandarAsAuditor'  }">Pernyataan Standar</RouterLink>
                    <RouterLink :to="{ name: 'FakultasListAsAuditor' }" class="block pl-[68px] py-2" :class="{ 'py-3 bg-neutral-300':
                                $route.name === 'FakultasListAsAuditor' ||
                                $route.name === 'CreateFakultasAsAuditor' ||
                                $route.name === 'UpdateFakultasAsAuditor' }">Fakultas</RouterLink>
                    <RouterLink :to="{ name: 'TipeAuditListAsAuditor' }" class="block pl-[68px] py-2" :class="{ 'py-3 bg-neutral-300':
                                $route.name === 'TipeAuditListAsAuditor' ||
                                $route.name === 'CreateTipeAuditAsAuditor' ||
                                $route.name === 'UpdateTipeAuditAsAuditor' }">Tipe Audit</RouterLink>
                </div>

                <!-- Audit Report as Admin -->
                <RouterLink v-if="userRole === 'admin'" :to="{ name: 'LaporanAuditAsAdmin' }" :style="{ width: sidebarWidth, minWidth: sidebarWidth }" :class="{ 'border-l-4 border-blue-500 pl-[15px] bg-neutral-300': $route.name === 'LaporanAuditAsAdmin' || $route.name === 'DetailStandarAsAdmin' || $route.name === 'DetailLaporanAsAdmin' || $route.name === 'VisualAuditAsAdmin' || $route.name === 'LaporanVisualAdmin' || $route.name === 'DetailVisualStandarAdmin' }" class="flex items-center gap-6 pl-5 py-3 text-nowrap transition-all duration-700 ease-in-out">
                    <div class="w-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/>
                            <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/>
                        </svg>
                    </div>
                    <p class="text-md">Laporan Audit</p>
                </RouterLink>

                <!-- Analysis Report as Admin -->
                <RouterLink v-if="userRole === 'admin'" :to="{ name: 'LaporanAnalysAsAdmin' }" :style="{ width: sidebarWidth, minWidth: sidebarWidth }" :class="{ 'border-l-4 border-blue-500 pl-[15px] bg-neutral-300': $route.name === 'LaporanAnalysAsAdmin' }" class="flex items-center gap-6 pl-5 py-3 text-nowrap transition-all duration-700 ease-in-out">
                    <div class="w-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard-data" viewBox="0 0 16 16">
                            <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0z"/>
                            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/>
                            <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/>
                        </svg>
                    </div>
                    <p class="text-md">Analys Audit</p>
                </RouterLink>

                <!-- Audit Report as Auditor -->
                <RouterLink v-if="userRole === 'auditor'" :to="{ name: 'LaporanAuditAsAuditor' }" :style="{ width: sidebarWidth, minWidth: sidebarWidth }" :class="{ 'border-l-4 border-blue-500 pl-[15px] bg-neutral-300': $route.name === 'LaporanAuditAsAuditor' || $route.name === 'DetailStandarAsAuditor' || $route.name === 'DetailLaporanAsAuditor' || $route.name === 'VisualAuditAsAuditor' || $route.name === 'LaporanVisualAuditor' || $route.name === 'DetailVisualStandarAuditor' }" class="flex items-center gap-6 pl-5 py-3 text-nowrap transition-all duration-700 ease-in-out">
                    <div class="w-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/>
                            <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/>
                        </svg>
                    </div>
                    <p class="text-md">Laporan Audit</p>
                </RouterLink>

                <!-- Analysis Report as Auditor -->
                <RouterLink v-if="userRole === 'auditor'" :to="{ name: 'LaporanAnalysAsAuditor' }" :style="{ width: sidebarWidth, minWidth: sidebarWidth }" :class="{ 'border-l-4 border-blue-500 pl-[15px] bg-neutral-300': $route.name === 'LaporanAnalysAsAuditor' }" class="flex items-center gap-6 pl-5 py-3 text-nowrap transition-all duration-700 ease-in-out">
                    <div class="w-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard-data" viewBox="0 0 16 16">
                            <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0z"/>
                            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/>
                            <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/>
                        </svg>
                    </div>
                    <p class="text-md">Analys Audit</p>
                </RouterLink>

            </div>

            <!-- Logout Menu -->
            <div @click="logout" class="flex items-center gap-6 p-5 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-box-arrow-left cursor-pointer" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                    <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                </svg>
                <p class="text-md">Logout</p>
            </div>
        </aside>
        <!-- Sidebar End -->

        <!-- Main Content Start -->
        <main class="w-full h-full z-0">
            <router-view/>
        </main>
        <!-- Main Content End -->
    </div>
</template>

<script setup lang="ts">
// Import Library
import { useRouter, useRoute } from "vue-router";
import { computed, ref, onMounted } from "vue";
import {useAuthStore} from "@/router/auth.js";

// Variable Declaration
const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();
const sidebarWidth = ref('64px');
const dropdownVisible = ref(false);

// Getter User Role
const userRole = computed(() => {
    return authStore.role;
});

// Getter Route Name
const isLoginPage = computed(() => route.name === 'login');

// OnMounted Method
onMounted(() => {
    console.log("User role on mount:", userRole.value);
});

// Toggle Sidebar Method
const toggleSidebar = () => {
    sidebarWidth.value = sidebarWidth.value === '64px' ? '250px' : '64px';
    dropdownVisible.value = false;
};

// Toggle Drop Down Method
const toggleDropdown = () => {
    if (sidebarWidth.value === '64px') {
        dropdownVisible.value = false;
    } else {
        dropdownVisible.value = !dropdownVisible.value;
    }
};

// Handle Click Method
const handleClick = () => {
    toggleSidebar();
    toggleDropdown();
};

// Logout Method
const logout = () => {
    authStore.logout();
    router.push({ name: 'login' });
};
</script>
