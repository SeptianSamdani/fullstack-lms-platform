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

export interface Lesson {
  id: number
  module_id: number
  title: string
  content_url: string | null
  content: string | null
  content_type: 'video' | 'text' | 'mixed'
  duration: number | null
  order: number
}

export interface Module {
  id: number
  course_id: number
  title: string
  order: number
  lessons: Lesson[]
}

export interface Review {
  id: number
  user: { id: number; name: string; avatar_url: string | null }
  rating: number
  comment: string | null
  created_at: string
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
  modules?: Module[]
}

export interface PaginatedResponse<T> {
  data: T[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}