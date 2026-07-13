export interface User {
  id: number
  name: string
  email: string
  avatar_url: string | null
  phone: string | null
  bio: string | null
  roles: string[]
  permissions?: string[]
}

export interface AuthResponse {
  user: User
  token: string
}

export interface FormErrors {
  [key: string]: string[]
}