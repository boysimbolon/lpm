import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/components/LoginPage.vue';
// Admin
import DashboardAdmin from "@/components/admin/Dashboard.vue";
import AssignAuditor from "@/components/admin/AssignAuditor.vue";
import AssignAuditeAsAdmin from "@/components/admin/AssignAudite.vue";
import UserListAsAdmin from "@/components/admin/UserList.vue";
import UserCreateAsAdmin from "@/components/admin/UserCreate.vue";
import UserEditAsAdmin from "@/components/admin/UserEdit.vue";
import StandarListAsAdmin from "@/components/admin/StandarList.vue";
import StandarViewAsAdmin from "@/components/admin/StandarView.vue";
import StandarCreateAsAdmin from "@/components/admin/StandarCreate.vue";
import StandarEditAsAdmin from "@/components/admin/StandarEdit.vue";
import PernyataanStandarCreateAsAdmin from "@/components/admin/PernyataanStandar.vue";
import PernyataanStandarEditAsAdmin from "@/components/admin/PernyataanStandarEdit.vue";
import FakultasListAsAdmin from "@/components/admin/FakultasList.vue";
import FakultasCreateAsAdmin from "@/components/admin/FakultasCreate.vue";
import FakultasEditAsAdmin from "@/components/admin/FakultasEdit.vue";
import TipeAuditListAsAdmin from "@/components/admin/TipeAuditList.vue";
import TipeAuditCreateAsAdmin from "@/components/admin/TipeAuditCreate.vue";
import TipeAuditEditAsAdmin from "@/components/admin/TipeAuditEdit.vue";
import LaporanAsAdmin from "@/components/admin/Laporan.vue";
import DetailLaporanAsAdmin from "@/components/admin/LaporanDetails.vue";
import DetailStandarAsAdmin from "@/components/admin/LaporanDetailStandar.vue";
import VisualAuditAsAdmin from "@/components/admin/LaporanVisual.vue";
import LaporanAnalysAsAdmin from "@/components/admin/LaporanAnalys.vue";
import DetailVisualStandarAsAdmin from "@/components/admin/LaporanVisualStandar.vue";

// Auditor
import DashboardAuditor from "@/components/auditor/Dashboard.vue";
import AssignAuditeAsAuditor from "@/components/auditor/AssignAudite.vue";
import GradingAsAuditor from "@/components/auditor/Grading.vue";
import ScoreEditAsAuditor from "@/components/auditor/ScoreEdit.vue";
import UserListAsAuditor from "@/components/auditor/UserList.vue";
import UserCreateAsAuditor from "@/components/auditor/UserCreate.vue";
import UserEditAsAuditor from "@/components/auditor/UserEdit.vue";
import StandarViewAsAuditor from "@/components/auditor/StandarView.vue";
import StandarListAsAuditor from "@/components/auditor/StandarList.vue";
import StandarCreateAsAuditor from "@/components/auditor/StandarCreate.vue";
import StandarEditAsAuditor from "@/components/auditor/StandarEdit.vue";
import PernyataanStandarCreateAsAuditor from "@/components/auditor/PernyataanStandar.vue";
import PernyataanStandarEditAsAuditor from "@/components/auditor/PernyataanStandarEdit.vue";
import FakultasListAsAuditor from "@/components/auditor/FakultasList.vue";
import FakultasCreateAsAuditor from "@/components/auditor/FakultasCreate.vue";
import FakultasEditAsAuditor from "@/components/auditor/FakultasEdit.vue";
import TipeAuditListAsAuditor from "@/components/auditor/TipeAuditList.vue";
import TipeAuditCreateAsAuditor from "@/components/auditor/TipeAuditCreate.vue";
import TipeAuditEditAsAuditor from "@/components/auditor/TipeAuditEdit.vue";
import LaporanAsAuditor from "@/components/auditor/Laporan.vue";
import DetailLaporanAsAuditor from "@/components/auditor/LaporanDetails.vue";
import LaporanAnalysAsAuditor from "@/components/auditor/LaporanAnalys.vue";
import DetailStandarAsAuditor from "@/components/auditor/LaporanDetailStandar.vue";
import DetailVisualStandarAsAuditor from "@/components/auditor/LaporanVisualStandar.vue";
import LaporanVisual from "@/components/auditor/LaporanVisual.vue";
import VisualAuditAsAuditor from "@/components/auditor/LaporanVisual.vue";

// Audite
import DashboardAudite from "@/components/audite/Dashboard.vue";
import UpdateMe from "@/components/audite/UserEdit.vue";
import FeedbackAuditee from "@/components/audite/FeedbackAuditee.vue";
import AuditStart from "@/components/audite/AuditStart.vue";
import EditFileAudit from "@/components/audite/EditFileAudit.vue";

// import ChartComponent from "@/components/ChartComponent.vue";
import { useAuthStore } from "@/router/auth.js";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
      // Login
      {
          path: '/',
          name: 'login',
          component: Login
      },
      // Dashboard Admin
      {
          path: '/',
          name: 'Admin',
          component: DashboardAdmin
      },
      // Assign Auditor As Admin
      {
          path: '/',
          name: 'AssignAuditorAsAdmin',
          component: AssignAuditor
      },
      // Assign Audite As Admin
      {
          path: '/',
          name: 'AssignAuditeAsAdmin',
          component: AssignAuditeAsAdmin
      },
      // User List As Admin
      {
          path: '/',
          name: 'UserListAsAdmin',
          component: UserListAsAdmin
      },
      // Create User As Admin
      {
          path: '/',
          name: 'CreateUserAsAdmin',
          component: UserCreateAsAdmin
      },
      // Update User As Admin
      {
          path: '/',
          name: 'UpdateUserAsAdmin',
          component: UserEditAsAdmin
      },
      // Standar List As Admin
      {
          path: '/',
          name: 'StandarListAsAdmin',
          component: StandarListAsAdmin
      },
      // Standar View As Admin
      {
          path: '/',
          name: 'StandarViewAsAdmin',
          component: StandarViewAsAdmin
      },
      // Create Standar As Admin
      {
          path: '/',
          name: 'CreateStandarAsAdmin',
          component: StandarCreateAsAdmin
      },
      // Update Standar As Admin
      {
          path: '/',
          name: 'UpdateStandarAsAdmin',
          component: StandarEditAsAdmin
      },
      // Create Pernyataan As Admin
      {
          path:'/',
          name:'CreatePernyataanStandarAsAdmin',
          component: PernyataanStandarCreateAsAdmin
      },
      // Update Pernyataan As Admin
      {
          path:'/',
          name:'EditPernyataanStandarAsAdmin',
          component: PernyataanStandarEditAsAdmin
      },
      // Fakultas List As Admin
      {
          path: '/',
          name: 'FakultasListAsAdmin',
          component: FakultasListAsAdmin
      },
      // Create Fakultas As Admin
      {
          path: '/',
          name: 'CreateFakultasAsAdmin',
          component: FakultasCreateAsAdmin
      },
      // Update Fakultas As Admin
      {
          path: '/',
          name: 'UpdateFakultasAsAdmin',
          component: FakultasEditAsAdmin
      },
      // Tipe Audit List As Admin
      {
          path: '/',
          name: 'TipeAuditListAsAdmin',
          component: TipeAuditListAsAdmin
      },
      // Create Tipe Audit As Admin
      {
          path: '/',
          name: 'CreateTipeAuditAsAdmin',
          component: TipeAuditCreateAsAdmin
      },
      // Update Tipe Audit As Admin
      {
          path: '/',
          name: 'UpdateTipeAuditAsAdmin',
          component: TipeAuditEditAsAdmin
      },
      // Laporan Audit As Admin
      {
          path: '/',
          name: 'LaporanAuditAsAdmin',
          component: LaporanAsAdmin
      },
      // Detail Laporan As Admin
      {
          path: '/',
          name: 'DetailLaporanAsAdmin',
          component: DetailLaporanAsAdmin
      },
      // Detail Laporan As Admin
      {
          path: '/',
          name: 'DetailStandarAsAdmin',
          component: DetailStandarAsAdmin
      },
      // Analys Laporan As Admin
      {
          path: '/',
          name: 'LaporanAnalysAsAdmin',
          component: LaporanAnalysAsAdmin
      },
      // {
      //     path: '/',
      //     name: 'ChartComponent',
      //     component: ChartComponent
      // },
      {
          path: '/',
          name: 'VisualAuditAsAdmin',
          component: VisualAuditAsAdmin
      },
      {
          path: '/',
          name: 'DetailVisualStandarAdmin',
          component: DetailVisualStandarAsAdmin
      },
      // Dashboard Auditor
      {
          path: '/',
          name: 'LPM',
          component: DashboardAuditor
      },
      // Assign Audite As Auditor
      {
          path: '/',
          name: 'AssignAuditeAsAuditor',
          component: AssignAuditeAsAuditor
      },
      // Grading As Auditor
      {
          path: '/',
          name: 'GradingAsAuditor',
          component: GradingAsAuditor
      },
      // Edit Score As Auditor
      {
          path: '/',
          name: 'EditScoreAsAuditor',
          component: ScoreEditAsAuditor
      },
      // User List As Auditor
      {
          path: '/',
          name: 'UserListAsAuditor',
          component: UserListAsAuditor
      },
      // Create User As Auditor
      {
          path: '/',
          name: 'CreateUserAsAuditor',
          component: UserCreateAsAuditor
      },
      // Update User As Auditor
      {
          path: '/',
          name: 'UpdateUserAsAuditor',
          component: UserEditAsAuditor
      },
      // Standar List As Auditor
      {
          path: '/',
          name: 'StandarViewAsAuditor',
          component: StandarViewAsAuditor
      },
      {
          path: '/',
          name: 'StandarListAsAuditor',
          component: StandarListAsAuditor
      },
      // Create Standar As Auditor
      {
          path: '/',
          name: 'CreateStandarAsAuditor',
          component: StandarCreateAsAuditor
      },
      // Update Standar As Auditor
      {
          path: '/',
          name: 'UpdateStandarAsAuditor',
          component: StandarEditAsAuditor
      },
      // Create Pernyataan As Auditor
      {
          path: '/',
          name: 'CreatePernyataanStandarAsAuditor',
          component: PernyataanStandarCreateAsAuditor
      },
      // Update Pernyataan As Auditor
      {
          path: '/',
          name: 'UpdatePernyataanStandarAsAuditor',
          component: PernyataanStandarEditAsAuditor
      },
      // Fakultas List As Auditor
      {
          path: '/',
          name: 'FakultasListAsAuditor',
          component: FakultasListAsAuditor
      },
      // Create Fakultas As Auditor
      {
          path: '/',
          name: 'CreateFakultasAsAuditor',
          component: FakultasCreateAsAuditor
      },
      // Edit Fakultas As Auditor
      {
          path: '/',
          name: 'UpdateFakultasAsAuditor',
          component: FakultasEditAsAuditor
      },
      // Tipe Audit List As Auditor
      {
          path: '/',
          name: 'TipeAuditListAsAuditor',
          component: TipeAuditListAsAuditor
      },
      // Create Tipe Audit As Auditor
      {
          path: '/',
          name: 'CreateTipeAuditAsAuditor',
          component: TipeAuditCreateAsAuditor
      },
      // Update Tipe Audit As Auditor
      {
          path: '/',
          name: 'UpdateTipeAuditAsAuditor',
          component: TipeAuditEditAsAuditor
      },
      // Laporan Audit As Auditor
      {
          path: '/',
          name: 'LaporanAuditAsAuditor',
          component: LaporanAsAuditor
      },
      // Detail Laporan As Auditor
      {
          path: '/',
          name: 'DetailLaporanAsAuditor',
          component: DetailLaporanAsAuditor
      },
      {
          path: '/',
          name: 'DetailStandarAsAuditor',
          component: DetailStandarAsAuditor
      },
    // Detail Laporan As Auditor
      {
          path: '/',
          name: 'LaporanAnalysAsAuditor',
          component: LaporanAnalysAsAuditor
      },
      {
          path: '/',
          name: 'LaporanVisualAuditor',
          component: LaporanVisual
      },
      {
          path: '/',
          name: 'VisualAuditAsAuditor',
          component: VisualAuditAsAuditor
      },
      {
          path: '/',
          name: 'DetailVisualStandarAuditor',
          component: DetailVisualStandarAsAuditor
      },
      // Dashboard Audite
      {
          path: '/',
          name: 'Audite',
          component: DashboardAudite
      },
      {
          path: '/',
          name: 'IsiDokumenAudit',
          component: AuditStart
      },
      {
          path: '/',
          name: 'EditFile',
          component: EditFileAudit
      },
      {
          path: '/',
          name: 'FeedbackAuditee',
          component: FeedbackAuditee
      },
      {
          path:'/',
          name:'UpdateMe',
          component: UpdateMe
      }
  ]
})

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
    if (to.name !== "login" && !authStore.isLoggedIn) {
        next({ name: "login" });
    } else {
        next();
    }
});
export default router
