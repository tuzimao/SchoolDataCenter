// ** React Imports
import { forwardRef, Fragment, ReactElement, Ref, useState } from 'react'

// ** MUI Imports
import IconButton from '@mui/material/IconButton'
import Grid from '@mui/material/Grid'
import Dialog from '@mui/material/Dialog'
import DialogContent from '@mui/material/DialogContent'
import Fade, { FadeProps } from '@mui/material/Fade'
import { Breakpoint } from '@mui/system';

// ** Icon Imports
import Icon from 'src/@core/components/icon'
import AddOrEditTableCore from './AddOrEditTableCore'
import { GridRowId } from '@mui/x-data-grid'

const Transition = forwardRef(function Transition(
  props: FadeProps & { children?: ReactElement<any, any> },
  ref: Ref<unknown>
) {
  return <Fade ref={ref} {...props} />
})

interface AddOrEditTableType {
  authConfig: any
  externalId: number
  id: string
  action: string
  addEditStructInfo: any
  open: boolean
  toggleAddTableDrawer: (val: string) => void
  addUserHandleFilter: (mobileEditPageIdEnableValue: boolean) => void
  backEndApi: string
  editViewCounter: number
  IsGetStructureFromEditDefault: number
  addEditViewShowInWindow: boolean
  CSRF_TOKEN: string
  dataGridLanguageCode: string
  dialogMaxWidth: Breakpoint
  toggleImagesPreviewListDrawer: (imagesPreviewList: string[], imagetype: string[]) => void
  handleIsLoadingTipChange: (status: boolean, showText: string) => void
  setForceUpdate: (value: any) => void
  additionalParameters: any
  selectedRows: GridRowId[]
  setSelectedRows: (value: any) => void
}

const AddOrEditTable = (props: AddOrEditTableType) => {
  // ** Props
  const { authConfig, externalId, id, action, addEditStructInfo, open, toggleAddTableDrawer, addUserHandleFilter, backEndApi, editViewCounter, IsGetStructureFromEditDefault, addEditViewShowInWindow, CSRF_TOKEN, dataGridLanguageCode, dialogMaxWidth, toggleImagesPreviewListDrawer, handleIsLoadingTipChange, setForceUpdate, additionalParameters, selectedRows, setSelectedRows } = props

  const handleClose = () => {
    toggleAddTableDrawer('HandleClose')
  }

  const addEditStructInfoNew = {...addEditStructInfo}
  if(addEditViewShowInWindow) {
    addEditStructInfoNew.canceltext = ""
  }

  const [submitCounter, setSubmitCounter] = useState<number>(0)

  return (
    <Fragment>
    {addEditViewShowInWindow ?
      <Grid sx={{ pb: 2, px: 1, mt: -2, position: 'relative' }} style={{ width: '100%' }}>
        <AddOrEditTableCore authConfig={authConfig} externalId={externalId} id={id} action={action} addEditStructInfo={addEditStructInfoNew} open={open} toggleAddTableDrawer={toggleAddTableDrawer} addUserHandleFilter={addUserHandleFilter} backEndApi={backEndApi} editViewCounter={editViewCounter + 1} IsGetStructureFromEditDefault={IsGetStructureFromEditDefault} AddtionalParams={{"AddtionalParams":"AddtionalParams"}} CSRF_TOKEN={CSRF_TOKEN} dataGridLanguageCode={dataGridLanguageCode} toggleImagesPreviewListDrawer={toggleImagesPreviewListDrawer} handleIsLoadingTipChange={handleIsLoadingTipChange} setForceUpdate={setForceUpdate} additionalParameters={additionalParameters} submitCounter={submitCounter} setSubmitCounter={setSubmitCounter} setFormSubmitStatus={()=>{console.log("setFormSubmitStatus")}} selectedRows={selectedRows} setSelectedRows={setSelectedRows}/>
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
        <DialogContent sx={{ pb: 5, px: 10, pt: 8, position: 'relative' }} style={{ height: `${addEditStructInfo.dialogContentHeight}`, width: '100%' }}>
          <IconButton
            size='small'
            onClick={handleClose}
            sx={{ position: 'absolute', top: '1rem', right: '1rem' }}
          >
            <Icon icon='mdi:close' />
          </IconButton>
          <AddOrEditTableCore authConfig={authConfig} externalId={externalId} id={id} action={action} addEditStructInfo={addEditStructInfo} open={open} toggleAddTableDrawer={toggleAddTableDrawer} addUserHandleFilter={addUserHandleFilter} backEndApi={backEndApi} editViewCounter={editViewCounter + 1} IsGetStructureFromEditDefault={0} AddtionalParams={{"AddtionalParams":"AddtionalParams"}} CSRF_TOKEN={CSRF_TOKEN} dataGridLanguageCode={dataGridLanguageCode} toggleImagesPreviewListDrawer={toggleImagesPreviewListDrawer} handleIsLoadingTipChange={handleIsLoadingTipChange} setForceUpdate={setForceUpdate} additionalParameters={additionalParameters} submitCounter={submitCounter} setSubmitCounter={setSubmitCounter} setFormSubmitStatus={()=>{console.log("setFormSubmitStatus")}} selectedRows={selectedRows} setSelectedRows={setSelectedRows}/>
        </DialogContent>
      </Dialog>
    }
    </Fragment>
  )
}

export default AddOrEditTable
