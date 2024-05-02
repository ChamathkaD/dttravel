<script setup>
import { ref } from 'vue'
import { Head, router, usePage } from "@inertiajs/vue3"
import UserStatistics from "@/pages/Users/UserStatistics.vue"
import UserCreateModal from "@/pages/Users/UserCreateModal.vue"
import { Confirm, Notify } from "notiflix"
import UserEditDialog from "@/pages/Users/Dialog/UserEditDialog.vue"
import { usePermission } from "@/composables/usePermission"

const props = defineProps({
  users: Object,
  roleNames: Array,
})

const search = ref('')
const isEditDialogVisible = ref(false)
const userData = ref({})
const selectedRole = ref()

const { hasRole } = usePermission()

const filteredUser = computed(() => {
  return selectedRole.value ? props.users.filter(user => user.role_name === selectedRole.value) : props.users
})

const handleEditDialog = item => {
  userData.value = item
  isEditDialogVisible.value = true
}

const headers = [
  { title: 'User', key: 'full_name' },
  { title: 'Role', key: 'role_name' },
  { title: 'Status', key: 'status' },
  { title: 'Last Login At', key: 'last_login_at' },
  { title: 'Actions', key: 'actions', sortable: false },
]

const resolveUserRoleVariant = role => {
  const roleLowerCase = role.toLowerCase()
  if (roleLowerCase === 'super administrator')
    return {
      color: 'error',
      icon: 'tabler-device-laptop',
    }
  if (roleLowerCase === 'staff member')
    return {
      color: 'success',
      icon: 'tabler-user',
    }
  if (roleLowerCase === 'traveler')
    return {
      color: 'primary',
      icon: 'tabler-user-heart',
    }
  if (roleLowerCase === 'travel agent')
    return {
      color: 'secondary',
      icon: 'tabler-user-square-rounded',
    }

  return {
    color: 'primary',
    icon: 'tabler-user',
  }
}

const resolveUserStatusVariant = status => {
  const statLowerCase = status.toLowerCase()
  if (statLowerCase === 'pending')
    return 'warning'
  if (statLowerCase === 'active')
    return 'success'
  if (statLowerCase === 'inactive')
    return 'secondary'

  return 'primary'
}

const handleUserDelete = user => {
  if (route().params.trashed) {
    Confirm.show(
      'Delete User Permanently?',
      'This user will be permanently deleted from the database. This cannot be undone.',
      'Delete',
      'No',
      () => {
        router.get(route("users.force-delete", user.id), {}, {
          preserveScroll: true,
          onSuccess: () => {
            Notify.success("User was successfully permanently deleted!")
          },
        })
      })
  } else {
    if (user.id === usePage().props.auth.user.id) {
      Notify.warning('We would feel really bad if you deleted yourself, please reconsider.')
    } else {
      Confirm.show(
        'User Delete',
        'Do you want to delete this user?',
        'Delete',
        'No',
        () => {
          router.delete(route("users.destroy", user.id), {
            preserveScroll: true,
            onSuccess: () => {
              Notify.success("User moved into trash!")
            },
          })
        })
    }
  }
}

const handleRestore = user => {
  Confirm.show(
    'User Restore',
    'Would you like to restore this user record?',
    'Restore',
    'Cancel',
    () => {
      router.get(route("users.restore", user.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
          Notify.success("User restored successfully!")
        },
      })
    })
}

const handleResetPassword = email => {
  router.post(route('send-password-reset-link'), {
    email,
  }, {
    onSuccess: () => {
      Notify.success(`Password Reset Link Sent to ${email}`)
    },
  })
}

const handleUserEnable = user => {
  router.get(route('user.enable-user', user.id), {}, {
    onSuccess: () => Notify.success(`${user.full_name} has been enabled`),
  })
}

const handleUserDisable = user => {
  router.get(route('user.disable-user', user.id), {}, {
    onSuccess: () => Notify.success(`${user.full_name} has been disabled`),
  })
}

const handleResendInvitation = user => {
  router.get(route('user.resend-invitation', user.id), {}, {
    onSuccess: () => Notify.success(`Send invitation mail to ${user.full_name}`),
  })
}
</script>

<template>
  <Head :title="hasRole('Staff Member') ? 'Agent List' : 'Users List'"/>
  <Breadcrumbs/>
  <section>
    <template v-if="!route().params.trashed">
      <UserStatistics
        v-if="!route().params.roleName"
        :users="users"
        :role-names="roleNames"
      />
    </template>

    <VCard>
      <VCardText>
        <VRow
          v-if="route().params.roleName"
          class="d-flex justify-end"
        >
          <UserCreateModal :roles="roleNames"/>
        </VRow>
        <VRow class="d-flex justify-space-between">
          <VCol
            cols="12"
            md="3"
            sm="6"
          >
            <AppTextField
              v-model="search"
              density="compact"
              placeholder="Search ..."
              append-inner-icon="tabler-search"
              single-line
              hide-details
              dense
              outlined
            />
          </VCol>
          <VCol
            cols="12"
            md="3"
            sm="6"
          >
            <AppSelect
              v-model="selectedRole"
              :items="['Travel Agent', 'Staff Member']"
              placeholder="Select Role"
              name="select"
              clearable
              clear-icon="tabler-x"
            />
          </VCol>
        </VRow>
      </VCardText>
      <VDataTable
        :headers="headers"
        :search="search"
        :items="filteredUser"
        :items-per-page="10"
        class="text-no-wrap"
      >
        <!-- User -->
        <template #item.full_name="{ item }">
          <div class="d-flex align-center">
            <VAvatar
              size="32"
              :color="item.profile_photo_path ? '' : 'primary'"
              :class="item.profile_photo_path ? '' : 'v-profile_photo_path-light-bg primary--text'"
              :variant="!item.profile_photo_path ? 'tonal' : undefined"
            >
              <VImg
                v-if="item.profile_photo_path"
                :src="item.profile_photo_url"
              />
              <span v-else>{{ avatarText(item.full_name.split(' ').slice(0, 2).join(' ')) }}</span>
            </VAvatar>
            <div class="d-flex flex-column ms-3">
              <span class="d-block font-weight-medium text-high-emphasis text-truncate">{{ item.full_name }}</span>
              <span class="text-sm text-medium-emphasis">
                {{ item.email }}
              </span>
            </div>
          </div>
        </template>

        <!-- Role -->
        <template #item.role_name="{ item }">
          <div class="d-flex align-center gap-4">
            <VAvatar
              :size="30"
              :color="resolveUserRoleVariant(item.role_name).color"
              variant="tonal"
            >
              <VIcon
                :size="20"
                :icon="resolveUserRoleVariant(item.role_name).icon"
              />
            </VAvatar>
            <span class="text-capitalize">{{ item.role_name }}</span>
          </div>
        </template>

        <!-- Status -->
        <template #item.status="{ item }">
          <VChip
            v-if="route().params.trashed"
            color="error"
            size="small"
            label
            class="text-capitalize"
          >
            Trashed
          </VChip>
          <VChip
            v-else
            :color="resolveUserStatusVariant(item.status)"
            size="small"
            label
            class="text-capitalize"
          >
            {{ item.status }}
          </VChip>
        </template>

        <!-- Actions -->
        <template #item.actions="{ item }">
          <IconBtn>
            <VIcon icon="tabler-dots-vertical"/>
            <VMenu activator="parent">
              <VList>
                <VListItem
                  prepend-icon="tabler-edit"
                  @click="handleEditDialog(item)"
                >
                  Edit
                </VListItem>

                <VListItem
                  v-if="route().params.trashed"
                  prepend-icon="tabler-restore"
                  @click="handleRestore(item)"
                >
                  Restore
                </VListItem>

                <VListItem
                  prepend-icon="tabler-trash"
                  :class="route().params.trashed ? 'text-error' : ''"
                  @click="handleUserDelete(item)"
                >
                  {{ route().params.trashed ? 'Delete Permanently' : 'Delete' }}
                </VListItem>

                <VListItem
                  v-if="hasRole('Super Administrator')"
                  prepend-icon="tabler-brand-ctemplar"
                  @click="handleResetPassword(item.email)"
                >
                  Reset Password
                </VListItem>

                <template v-if="hasRole('Super Administrator')">
                  <VListItem
                    v-if="item.role_name !== 'Super Administrator'"
                    v-show="item.is_ban == 0"
                    prepend-icon="tabler-user-cancel"
                    @click.prevent="handleUserDisable(item)"
                  >
                    Disable
                  </VListItem>

                  <VListItem
                    v-if="item.role_name !== 'Super Administrator'"
                    v-show="item.is_ban == 1"
                    prepend-icon="tabler-user-up"
                    @click.prevent="handleUserEnable(item)"
                  >
                    Enable
                  </VListItem>
                </template>
              </VList>
            </VMenu>
          </IconBtn>

          <IconBtn
            v-show="item.status === 'Invited'"
            @click="handleResendInvitation(item)"
          >
            <VIcon icon="tabler-mail-up"/>
            <VTooltip
              activator="parent"
              location="top"
            >
              Invite
            </VTooltip>
          </IconBtn>
        </template>
      </VDataTable>
    </VCard>
  </section>

  <!-- User Edit Dialog -->
  <UserEditDialog
    v-model:is-dialog-visible="isEditDialogVisible"
    :user-data="userData"
    :user-roles="roleNames"
  />
</template>
