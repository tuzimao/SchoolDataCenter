// ** React Imports
import { ReactNode, ReactElement } from 'react'

// ** Hooks Import
import { useAuth } from 'src/hooks/useAuth'

interface GuestGuardProps {
  children: ReactNode
  fallback: ReactElement | null
}

const AuthAndGuestGuard = (props: GuestGuardProps) => {
  const { children, fallback } = props
  const auth = useAuth()

  if (auth.loading) {
    return fallback
  }

  return <>{children}</>
}

export default AuthAndGuestGuard
