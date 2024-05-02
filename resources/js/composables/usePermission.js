import { usePage } from "@inertiajs/vue3"

export const usePermission = () => {
  const hasRole = name => usePage().props.auth.user.roles.includes(name)

  return { hasRole }
}
