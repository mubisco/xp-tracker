import { Experience } from '@/Domain/Character/Experience'
import { EventAware } from '@/Domain/Shared/Event/EventAware'

export interface Party extends EventAware {
  updateExperience (experiencePoints: Experience): void
}
