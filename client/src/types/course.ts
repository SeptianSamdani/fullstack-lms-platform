export interface Category {
  id: number
  name: string
  icon_url: string | null
  courses_count?: number
}

export interface Instructor {
  id: number
  name: string
}

export interface Course {
  id: number
  title: string
  description: string | null
  instructor?: Instructor
  category?: Category
  thumbnail_url: string | null
  type: 'free' | 'paid'
  price: string | number
  status: 'draft' | 'published'
  enrollments_count?: number
  reviews_avg_rating?: number | null
  reviews_count?: number
  is_enrolled?: boolean
}

export interface PaginatedResponse<T> {
  data: T[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}