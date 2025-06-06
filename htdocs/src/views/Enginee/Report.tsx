// ** React Imports
import { forwardRef, ReactElement, Ref, Fragment } from 'react'

// ** MUI Imports
import IconButton from '@mui/material/IconButton'
import Dialog from '@mui/material/Dialog'
import DialogContent from '@mui/material/DialogContent'
import Fade, { FadeProps } from '@mui/material/Fade'

// ** Icon Imports
import Icon from 'src/@core/components/icon'
import Grid from '@mui/material/Grid'

import ReportCore from './ReportCore'
import { Breakpoint } from '@mui/system'

import { authConfig } from 'src/configs/auth'

const Transition = forwardRef(function Transition(
  props: FadeProps & { children?: ReactElement<any, any> },
  ref: Ref<unknown>
) {
  return <Fade ref={ref} {...props} />
})

const ViewTable = () => { //props: ViewTableType
  // ** Props
  //const { authConfig, externalId, id, action, pageJsonInfor, open, toggleViewTableDrawer, backEndApi, editViewCounter, addEditViewShowInWindow, CSRF_TOKEN, toggleImagesPreviewListDrawer, dialogMaxWidth, handleSetRightButtonIconOriginal, viewPageShareStatus, handSetViewPageShareStatus } = props


  const handleClose = () => {
    
    //toggleViewTableDrawer()
  }

  const addEditViewShowInWindow = true
  const externalId = 0
  const action = 'report_default'
  const backEndApi = 'data_report.php'
  const editViewCounter = 0
  const dialogMaxWidth = 'lg' as Breakpoint
  const open = true


  return (
    <Fragment>
    {addEditViewShowInWindow ?
      <Grid sx={{ pb: 2, px: 0, pt: 1, position: 'relative' }} style={{ width: '100%' }}>
        <ReportCore authConfig={authConfig} externalId={Number(externalId)} action={action} backEndApi={backEndApi} editViewCounter={editViewCounter + 1} />
      </Grid>
      :
      <Dialog
        fullWidth
        open={open}
        maxWidth={dialogMaxWidth}
        scroll='body'
        onClose={handleClose}
        TransitionComponent={Transition}
      >
        <DialogContent sx={{ pb: 8, pl: { xs: 4, sm: 6 }, pr: { xs: 0, sm: 6 }, pt: { xs: 8, sm: 12.5 }, position: 'relative' }}>
          <IconButton
            size='small'
            onClick={handleClose}
            sx={{ position: 'absolute', right: '1rem', top: '1rem' }}
          >
            <Icon icon='mdi:close' />
          </IconButton>
          <ReportCore authConfig={authConfig} externalId={Number(externalId)} action={action} backEndApi={backEndApi} editViewCounter={editViewCounter + 1} />
        </DialogContent>
      </Dialog >
    }
  </Fragment>
  )
}

export default ViewTable
