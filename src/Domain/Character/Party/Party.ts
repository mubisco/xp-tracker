import { Experience } from '@/Domain/Character/Experience'

export interface Party {
  updateExperience (experiencePoints: Experience): void
}
