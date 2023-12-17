import { VueEventBus } from '@/Infrastructure/Shared/Event/VueEventBus'
import { describe, expect, test } from 'vitest'
import { DummyEventListenerSpy } from './DummyEventListenerSpy'
import { SomeNiceEvent } from './SomeNiceEvent'

describe('Testing VueEventBus', () => {
  test('It should call listener when event published', async () => {
    const sut = new VueEventBus()
    const dummyEventListener = new DummyEventListenerSpy('SomeNiceEvent')
    const anotherDummyEventListener = new DummyEventListenerSpy('AnotherNiceEvent')
    sut.subscribe(dummyEventListener)
    sut.subscribe(anotherDummyEventListener)
    const event = new SomeNiceEvent()
    await sut.publish([event])
    expect(dummyEventListener._timesCalled).toBe(1)
    expect(anotherDummyEventListener._timesCalled).toBe(0)
  })
})
