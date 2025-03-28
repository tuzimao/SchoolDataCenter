import { useState, useEffect, Fragment, forwardRef, ReactElement, Ref } from 'react';
import axios from 'axios';
import { Box,Toolbar,AppBar,List,ListItem,ListItemButton,ListItemIcon,ListItemText,Typography,Paper,Button,Grid,styled, } from '@mui/material';
import { Home, Settings } from '@mui/icons-material';
import { authConfig, defaultConfig } from 'src/configs/auth'
import CircularProgress from '@mui/material/CircularProgress'

import AddOrEditTableCore from 'src/views/Enginee/AddOrEditTableCore'
import GetNextApprovalUsers from './GetNextApprovalUsers'

import Icon from 'src/@core/components/icon'
import IconButton from '@mui/material/IconButton'
import Dialog from '@mui/material/Dialog'
import DialogContent from '@mui/material/DialogContent'
import Fade, { FadeProps } from '@mui/material/Fade'

const Transition = forwardRef(function Transition(
  props: FadeProps & { children?: ReactElement<any, any> },
  ref: Ref<unknown>
) {
  return <Fade ref={ref} {...props} />
})

// 自定义滚动区域样式
const ScrollableContent = styled(Box)(({ theme }) => ({
  flexGrow: 1,
  overflowY: 'auto',
  padding: theme.spacing(3),
}));

// 自定义侧边栏样式
const Sidebar = styled(Paper)(({ theme }) => ({
  width: 80,
  padding: theme.spacing(2),
}));

const StartModel = ({ FlowId, handleReturnButton, flowRecord }: any) => {

  const [loading, setLoading] = useState(true);
  const [flowInfor, setFlowInfor] = useState<any>(null);
  const [submitCounter, setSubmitCounter] = useState<number>(0)
  const [dialogStatus, setDialogStatus] = useState<boolean>(false);
  const [refuseStatus, setRefuseStatus] = useState<boolean>(false);
  const [nextStepStatus, setNextStepStatus] = useState<boolean>(false);
  const [formSubmitStatus, setFormSubmitStatus] = useState<any>(null);
  
  useEffect(() => {
    const fetchWorkItems = async () => {
      try {
        const storedToken = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
        const response = await axios.post(authConfig.backEndApiHost + 'workflow/start.php?action=NewWorkflow', { FlowId }, {
          headers: {
            Authorization: storedToken,
            'Content-Type': 'application/json'
          }
        });
        const data = response.data;
        setFlowInfor(data.data)
      } 
      catch (err) {
        setLoading(false);
      } 
      finally {
        setLoading(false);
      }
    };

    FlowId && FlowId != undefined && flowRecord == null && fetchWorkItems();
    if(flowRecord) {
      setLoading(false)
      setFlowInfor({...flowRecord, id: flowRecord.工作ID2})
    }

  }, [FlowId]);

  console.log("flowInfor", flowInfor)

  const handleSaveData = () => {
    setSubmitCounter(submitCounter+1)
    console.log("handleSaveData", "handleSaveData")    
  }

  const handleToNextStep = async () => {
    setSubmitCounter(submitCounter+1)
    setDialogStatus(true)
    setNextStepStatus(true)
    setRefuseStatus(false)
    console.log("flowRecord", flowRecord)    
  }

  const handleRefuseButton = async () => {
    setDialogStatus(true)
    setNextStepStatus(false)
    setRefuseStatus(true)
    console.log("handleRefuseButton", flowRecord)    
  }

  

  const toggleAddTableDrawer = () => {
    console.log("toggleAddTableDrawer")
  }

  const addUserHandleFilter = () => {
    console.log("toggleAddTableDrawer")
  }

  const toggleImagesPreviewListDrawer = () => {
    console.log("toggleAddTableDrawer")
  }

  const handleIsLoadingTipChange = () => {
    console.log("toggleAddTableDrawer")
  }

  const setForceUpdate = () => {
    console.log("toggleAddTableDrawer")
  }

  const handleNextApprovalUsersDialogClose = () => {
    setDialogStatus(false)
    setNextStepStatus(false)
    setRefuseStatus(false)
  }

  const handleGobackToPreviousStep = async (GobackToStep: string) => {
    try {
      const storedToken = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
      const response = await axios.post(authConfig.backEndApiHost + 'workflow/start.php?action=GobackToPreviousStep', { FlowId, GobackToStep, processid: flowRecord.processid, runid: flowRecord.runid }, {
        headers: {
          Authorization: storedToken,
          'Content-Type': 'application/json'
        }
      });
      const data = response.data;
      console.log("handleGobackToPreviousStep", data)
      handleNextApprovalUsersDialogClose()
    } 
    catch (err) {
      handleNextApprovalUsersDialogClose()
    } 
    finally {
      handleNextApprovalUsersDialogClose()
    }
    handleReturnButton()
  }

  const backEndApi = "/data_workflow.php";
  const AddtionalParams = { FlowId, processid: flowRecord.processid, runid: flowRecord.runid }

  return (
    <Fragment>
      {loading == true && (
        <Grid item xs={12} sm={12} container justifyContent="space-around">
          <Box sx={{ mt: 6, mb: 6, display: 'flex', alignItems: 'center', flexDirection: 'column' }}>
              <CircularProgress size={45} />
              <Typography sx={{ mt: 6, mb: 6 }}>加载中...</Typography>
          </Box>
        </Grid>
      )}
      {loading == false && (        
        <Box sx={{ display: 'flex', flexDirection: 'column', height: 'calc(100vh - 64px)' }}>
          {/* 顶部 AppBar */}
          <AppBar position="static" color="default" sx={{minHeight: '50px', borderTopLeftRadius: '8px', borderTopRightRadius: '8px' }}>
            <Toolbar sx={{minHeight: '50px'}}>
              <Typography variant="h6" component="div" sx={{ flexGrow: 1 }}>
                {flowInfor.工作名称}
              </Typography>
              <Typography variant="body2">
                主办 {flowInfor.经办步骤}
              </Typography>
            </Toolbar>
          </AppBar>
  
          {/* 内容区域 (包括侧边栏和中间滚动区域) */}
          <Box sx={{ display: 'flex', flexGrow: 1 }} color="default" >
            {/* 左侧固定侧边栏 */}
            <Sidebar sx={{ borderRadius: 0, width: '60px' }}>
              <List>
                {['表单', '附件', '会签', '流程'].map((text, index) => (
                  <ListItem key={text} disablePadding>
                    <ListItemButton sx={{ flexDirection: 'column', alignItems: 'center' }}>
                      <ListItemIcon sx={{ minWidth: 'auto', mb: 0 }}>
                        {index % 2 === 0 ? <Home /> : <Settings />}
                      </ListItemIcon>
                      <ListItemText primary={text} sx={{ textAlign: 'center', whiteSpace: 'nowrap', wordWrap: 'break-word', }} />
                    </ListItemButton>
                  </ListItem>
                ))}
              </List>
            </Sidebar>
  
            {/* 中间滚动区域 */}
            <ScrollableContent>
              <Paper sx={{ padding: 2 }}>
                <Grid sx={{ mx: 2, px: 2, pb: 2}}>
                  <AddOrEditTableCore authConfig={authConfig} externalId={0} id={flowInfor.id} action={'edit_default'} addEditStructInfo={{allFields:{}, }} open={true} toggleAddTableDrawer={toggleAddTableDrawer} addUserHandleFilter={addUserHandleFilter} backEndApi={backEndApi} editViewCounter={1} IsGetStructureFromEditDefault={1} AddtionalParams={AddtionalParams} CSRF_TOKEN={""} dataGridLanguageCode={'zhCN'} toggleImagesPreviewListDrawer={toggleImagesPreviewListDrawer} handleIsLoadingTipChange={handleIsLoadingTipChange} setForceUpdate={setForceUpdate} additionalParameters={AddtionalParams} submitCounter={submitCounter} setSubmitCounter={setSubmitCounter} setFormSubmitStatus={setFormSubmitStatus}/>
                </Grid>
              </Paper>
            </ScrollableContent>
          </Box>
  
          {/* 底部固定 Toolbar */}
          <AppBar position="static" color="default" sx={{ top: 'auto', bottom: 0, minHeight: '50px' }}>
            {flowRecord && flowRecord.步骤状态 != "办结" && (              
              <Toolbar sx={{minHeight: '50px'}}>
                <Button variant="contained" size="small" sx={{ ml: 'auto' }} onClick={()=>{
                  handleToNextStep()
                }}>
                  转交下一步
                </Button>
                <Button variant="outlined" size="small" sx={{ ml: 2 }} onClick={()=>{
                  handleSaveData()
                }}>
                  保存
                </Button>
                {flowRecord && flowRecord.流程步骤ID && Number(flowRecord.流程步骤ID) > 1 && (
                  <Button variant="contained" size="small" sx={{ ml: 2 }} onClick={()=>{
                    handleRefuseButton()
                  }}>
                    退回
                  </Button>
                )}
                <Button variant="outlined" size="small" sx={{ ml: 2 }} onClick={()=>{
                  handleReturnButton()
                }}>
                  返回
                </Button>
              </Toolbar>
            )}
            {flowRecord && flowRecord.步骤状态 == "办结" && (              
              <Toolbar sx={{minHeight: '50px'}}>
                <Button variant="outlined" size="small" sx={{ ml: 'auto' }} onClick={()=>{
                  handleReturnButton()
                }}>
                  返回
                </Button>
              </Toolbar>
            )}
          </AppBar>
        </Box>
      )}
      {dialogStatus && (
        <Dialog
          fullWidth
          open={dialogStatus}
          scroll='body'
          onClose={handleNextApprovalUsersDialogClose}
          TransitionComponent={Transition}
        >
          <DialogContent sx={{ pb: 8, pl: { xs: 4, sm: 6 }, pr: { xs: 0, sm: 6 }, pt: { xs: 8, sm: 12.5 }, position: 'relative' }}>
            <IconButton
              size='small'
              onClick={handleNextApprovalUsersDialogClose}
              sx={{ position: 'absolute', right: '1rem', top: '1rem' }}
            >
              <Icon icon='mdi:close' />
            </IconButton>
            {nextStepStatus && (
              <GetNextApprovalUsers FlowId={FlowId} handleReturnButton={handleReturnButton} flowRecord={flowRecord} formSubmitStatus={formSubmitStatus}/>
            )}
            {refuseStatus && (
              <Grid item xs={12} sm={12} container justifyContent="space-around">
                <Box sx={{ mt: 6, mb: 6, display: 'flex', alignItems: 'center' }}>
                  <Button variant="contained" size="small" sx={{ ml: 2 }} onClick={()=>{
                    handleGobackToPreviousStep('PreviousStep')
                  }}>
                    退回到上一步
                  </Button>
                  <Button variant="outlined" size="small" sx={{ ml: 2 }} onClick={()=>{
                    handleGobackToPreviousStep('FirstStep')
                  }}>
                    退回到第一步
                  </Button>
                </Box>
              </Grid>
            )}
          </DialogContent>
        </Dialog >
      )}
    </Fragment>
  );
};

export default StartModel;
