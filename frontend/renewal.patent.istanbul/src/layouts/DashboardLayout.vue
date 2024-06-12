<template>
  <q-layout view="lHh Lpr lFf" class="bg-white dashboard-layout">
    <q-header elevated class="dashboard-header bg-primary">
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          @click="toggleLeftDrawer"
          aria-label="Menu"
          icon="menu"
        />

        <q-toolbar-title> Dashboard </q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" show-if-above bordered class="bg-grey-0">
      <q-list class="q-ml-lg">
        <q-item>
          <a :href="`/`">
            <img width="180" height="86" src="/images/patist-logo.png" />
          </a>
        </q-item>
        <!-- Admin Routes -->
        <q-item class="q-mt-lg" clickable to="/dashboard/admin"  v-if="['proust-admin', 'proust-user'].includes(role)">
          <q-item-section avatar>
            <q-icon name="home" style="color: #718096" />
          </q-item-section>
          <q-item-section>
            <q-item-label style="color: #718096"> Dashboard</q-item-label>
            <q-item-label caption style="color: #718096">
              Information about the site</q-item-label
            >
          </q-item-section>
        </q-item>
        <q-separator class="q-my-md" />

        <q-item
          clickable
          class="q-mt-lg"
          rel="noopener"
          to="/dashboard/renewals"
        >
          <q-item-section avatar>
            <q-icon name="list" style="color: #718096" />
          </q-item-section>
          <q-item-section>
            <q-item-label
              ><span class="text-primary"
                >Patent Renewal List</span
              ></q-item-label
            >
            <q-item-label caption
              ><span style="color: #718096"
                >Patent List Followed</span
              ></q-item-label
            >
          </q-item-section>
        </q-item>

        <q-item class="q-mt-lg" clickable to="/dashboard/new-application">
          <q-item-section avatar>
            <q-icon name="add" style="color: #718096" />
          </q-item-section>
          <q-item-section>
            <q-item-label style="color: #718096">
              Add New Application</q-item-label
            >
            <q-item-label caption style="color: #718096">
              Add an application to follow</q-item-label
            >
          </q-item-section>
        </q-item>
        <q-separator class="q-my-md"  v-if="['proust-admin', 'proust-user', 'client-admin'].includes(role)"/>


        <q-item
        v-if="['proust-admin', 'proust-user', 'client-admin'].includes(role)"
          clickable
          class="q-mt-lg"
          rel="noopener"
          to="/dashboard/admin/reminders"
        >
          <q-item-section avatar>
            <q-icon name="list" style="color: #718096" />
          </q-item-section>
          <q-item-section>
            <q-item-label
              ><span class="text-primary">Reminder List</span></q-item-label
            >
            <q-item-label caption
              ><span style="color: #718096"
                >Reminder List Followed</span
              ></q-item-label
            >
          </q-item-section>
        </q-item>

        <q-item class="q-mt-lg" clickable to="/dashboard/admin/reminder/add" v-if="['proust-admin', 'proust-user', 'client-admin'].includes(role)">
          <q-item-section avatar>
            <q-icon name="add" style="color: #718096" />
          </q-item-section>
          <q-item-section>
            <q-item-label style="color: #718096"> Add Reminder</q-item-label>
            <q-item-label caption style="color: #718096">
              Add a custom reminder</q-item-label
            >
          </q-item-section>
        </q-item>
        <q-item class="q-mt-lg" clickable to="/dashboard/admin/companies"  v-if="['proust-admin', 'proust-user'].includes(role)">
          <q-item-section avatar>
            <q-icon name="list" style="color: #718096" />
          </q-item-section>
          <q-item-section>
            <q-item-label style="color: #718096"> Companies</q-item-label>
            <q-item-label caption style="color: #718096">
              Information about companies</q-item-label
            >
          </q-item-section>
        </q-item>
        <q-item class="q-mt-lg" clickable to="/dashboard/admin/teams"  v-if="['proust-admin', 'proust-user', 'client-admin'].includes(role)">
          <q-item-section avatar>
            <q-icon name="list" style="color: #718096" />
          </q-item-section>
          <q-item-section>
            <q-item-label style="color: #718096"> Teams</q-item-label>
            <q-item-label caption style="color: #718096">
              Add teammates or edit teams</q-item-label
            >
          </q-item-section>
        </q-item>

        <q-item class="q-mt-lg" clickable to="/dashboard/admin/failed-requests"  v-if="['proust-admin', 'proust-user'].includes(role)">
          <q-item-section avatar>
            <q-icon name="error" style="color: #718096" />
          </q-item-section>
          <q-item-section>
            <q-item-label style="color: #718096"> Failed Requests</q-item-label>
            <q-item-label caption style="color: #718096">
              Error logs during renewal request</q-item-label
            >
          </q-item-section>
        </q-item>

        <!-- Admin Routes End -->

        <q-separator class="q-my-md" />
        <q-item clickable :to="`/dashboard/contact`" class="q-mt-lg">
          <q-item-section avatar>
            <q-icon name="contact_support" style="color: #718096" />
          </q-item-section>
          <q-item-section>
            <q-item-label style="color: #718096">Contact</q-item-label>
          </q-item-section>
        </q-item>
        <q-item clickable :to="`/dashboard/profile`" class="q-mt-lg">
          <q-item-section avatar>
            <q-icon name="settings" style="color: #718096" />
          </q-item-section>
          <q-item-section>
            <q-item-label style="color: #718096">Profile</q-item-label>
            <!-- <q-item-label caption><span style="color: #718096">{{ user.name }}</span></q-item-label> -->
          </q-item-section>
        </q-item>
        <q-item clickable @click="logout" class="q-mt-lg">
          <q-item-section avatar>
            <q-icon name="logout" style="color: #718096" />
          </q-item-section>
          <q-item-section>
            <q-item-label style="color: #718096">Logout</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>
    <q-page-container style="padding-top: 0px">
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { ref } from "vue";
import { useAuthStore } from "src/stores/useAuthStore";
import { useRouter } from "vue-router";
import { onMounted } from "vue";
import { storeToRefs } from "pinia";
export default {
  name: "MyLayout",
  setup() {
    const route = useRouter();
    const { user } = storeToRefs(useAuthStore());
    const lang = ref("");
    const role = ref("");
    const leftDrawerOpen = ref(false);
    function toggleLeftDrawer() {
      leftDrawerOpen.value = !leftDrawerOpen.value;
    }

    function logout() {
      useAuthStore().logout();
      route.push("/login");
    }
    onMounted(() => {
      if(useAuthStore().isAuthenticated === false){
        route.push("/login");
      }
      else{
        const { token, user } = storeToRefs(useAuthStore());
        role.value = user.value.role;
      }
    });

    return {
      lang,
      leftDrawerOpen,
      toggleLeftDrawer,
      logout,
      role
    };
  },
};
</script>
