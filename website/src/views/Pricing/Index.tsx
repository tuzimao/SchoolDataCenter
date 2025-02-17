'use client'

// React Imports
import { useEffect } from 'react'

// Component Imports
import PricingSection from '@/views/Pricing/PricingSection'
import FreeTrial from './FreeTrial'
import Plans from './Plans'
import Faqs from './Faqs'
import { useSettings } from '@core/hooks/useSettings'

// Type Imports

const PricingWrapper = () => {
  // Hooks
  const { updatePageSettings } = useSettings()

  // For Page specific settings
  useEffect(() => {
    return updatePageSettings({
      skin: 'default'
    })
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])

  return (
    <>
      <PricingSection />
      <FreeTrial />
      <Plans />
      <Faqs />
    </>
  )
}

export default PricingWrapper
