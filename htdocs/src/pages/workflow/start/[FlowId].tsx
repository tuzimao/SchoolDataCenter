// ** Next Import
import { Fragment } from 'react'
import { useRouter } from 'next/router'

// ** Demo Components Imports
import StartModel from 'src/views/Workflow/Start'

const TabHeaderTab = () => {
  const router = useRouter()
  const _GET = router.query
  const FlowId = String(_GET.FlowId)

  return (
    <Fragment>
      <StartModel FlowId={FlowId} />
    </Fragment>
  )
}

export default TabHeaderTab
