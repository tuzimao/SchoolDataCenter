import { useState, useEffect, Fragment } from 'react';
import axios from 'axios';
import { Box,Toolbar,AppBar,List,ListItem,ListItemButton,ListItemIcon,ListItemText,Typography,Paper,Button,Grid,styled, } from '@mui/material';
import { Home, Settings } from '@mui/icons-material';
import { authConfig, defaultConfig } from 'src/configs/auth'
import CircularProgress from '@mui/material/CircularProgress'

import AddOrEditTableCore from 'src/views/Enginee/AddOrEditTableCore'

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

const StartModel = ({ FlowId, handleReturnButton }: any) => {

  const [loading, setLoading] = useState(true);
  const [flowInfor, setFlowInfor] = useState<any>(null);
  const [submitCounter, setSubmitCounter] = useState<number>(0)

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

    FlowId && FlowId != undefined && fetchWorkItems();

  }, [FlowId]);

  console.log("flowInfor", flowInfor)

  const handleSaveData = () => {
    setSubmitCounter(submitCounter+1)
    console.log("handleSaveData", "handleSaveData")    
  }

  const handleToNextStep = () => {
    setSubmitCounter(0)
    console.log("handleToNextStep", "handleToNextStep")    
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
  
  const backEndApi = "/data_workflow.php";
  const idValue = "dlpTRmdZcVZIaU0wcE1FUGN4MmxrZ3x8Ojo2TVhVWHJSMjFuQVlqTEFpRWxHMXJ3fHw|"
  const AddtionalParams = { FlowId }

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
          <AppBar position="static" sx={{minHeight: '50px'}}>
            <Toolbar sx={{minHeight: '50px'}}>
              <Typography variant="h6" component="div" sx={{ flexGrow: 1 }}>
                {flowInfor.工作名称}
              </Typography>
              <Typography variant="body2">
                {flowInfor.步骤名称}
              </Typography>
            </Toolbar>
          </AppBar>
  
          {/* 内容区域 (包括侧边栏和中间滚动区域) */}
          <Box sx={{ display: 'flex', flexGrow: 1 }}>
            {/* 左侧固定侧边栏 */}
            <Sidebar sx={{ borderRadius: 0, minWidth: '60px' }}>
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
                  <AddOrEditTableCore authConfig={authConfig} externalId={0} id={idValue} action={'edit_default'} addEditStructInfo={{allFields:{}, }} open={true} toggleAddTableDrawer={toggleAddTableDrawer} addUserHandleFilter={addUserHandleFilter} backEndApi={backEndApi} editViewCounter={1} IsGetStructureFromEditDefault={1} AddtionalParams={AddtionalParams} CSRF_TOKEN={""} dataGridLanguageCode={'zhCN'} toggleImagesPreviewListDrawer={toggleImagesPreviewListDrawer} handleIsLoadingTipChange={handleIsLoadingTipChange} setForceUpdate={setForceUpdate} additionalParameters={AddtionalParams} submitCounter={submitCounter} setSubmitCounter={setSubmitCounter}/>
                </Grid>
              </Paper>
            </ScrollableContent>
          </Box>
  
          {/* 底部固定 Toolbar */}
          <AppBar position="static" color="default" sx={{ top: 'auto', bottom: 0, minHeight: '50px' }}>
            <Toolbar sx={{minHeight: '50px'}}>
              <Button variant="contained" size="small" sx={{ ml: 'auto' }} onClick={()=>{
                handleToNextStep()
              }}>
                转文下一步
              </Button>
              <Button variant="outlined" size="small" sx={{ ml: 2 }} onClick={()=>{
                handleSaveData()
              }}>
                保存
              </Button>
              <Button variant="outlined" size="small" sx={{ ml: 2 }} onClick={()=>{
                handleSaveData()
                handleReturnButton()
              }}>
                保存返回
              </Button>
              <Button variant="outlined" size="small" sx={{ ml: 2 }} onClick={()=>{
                handleReturnButton()
              }}>
                返回
              </Button>
            </Toolbar>
          </AppBar>
        </Box>
      )}
    </Fragment>
  );
};

export default StartModel;
