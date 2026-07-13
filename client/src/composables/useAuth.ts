import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/lib/axios'
import type { User, AuthResponse, FormErrors } from '@/types/auth'

// State di-share antar komponen (module-level, tanpa Pinia dulu)
const user = ref<User | null>(JSON.parse(localStorage.getItem('auth_user') || 'null'))
const token = ref<string | null>(localStorage.getItem('auth_token'))

export function useAuth() {
  const router = useRouter()
  const loading = ref(false)
  const errors = ref<FormErrors>({})

  const isAuthenticated = () => !!token.value

  const setSession = (data: AuthResponse) => {
    token.value = data.token
    user.value = data.user
    localStorage.setItem('auth_token', data.token)
    localStorage.setItem('auth_user', JSON.stringify(data.user))
  }

  const handleError = (error: any) => {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    } else {
      errors.value = { general: [error.response?.data?.message || 'Terjadi kesalahan. Coba lagi.'] }
    }
  }

  const login = async (payload: { email: string; password: string }) => {
    loading.value = true
    errors.value = {}
    try {
      const { data } = await api.post<AuthResponse>('/login', payload)
      setSession(data)
      router.push('/dashboard')
    } catch (error) {
      handleError(error)
      throw error
    } finally {
      loading.value = false
    }
  }

  const register = async (payload: {
    name: string
    email: string
    password: string
    password_confirmation: string
  }) => {
    loading.value = true
    errors.value = {}
    try {
      const { data } = await api.post<AuthResponse>('/register', payload)
      setSession(data)
      router.push('/dashboard')
    } catch (error) {
      handleError(error)
      throw error
    } finally {
      loading.value = false
    }
  }

  const logout = async () => {
    try {
      await api.post('/logout')
    } finally {
      token.value = null
      user.value = null
      localStorage.removeItem('auth_token')
      localStorage.removeItem('auth_user')
      router.push('/login')
    }
  }

  return { user, token, loading, errors, isAuthenticated, login, register, logout }
}