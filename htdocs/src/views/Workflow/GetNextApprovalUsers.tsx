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

const GetNextApprovalUsers = ({ FlowId, handleReturnButton, flowRecord }: any) => {

  const [loading, setLoading] = useState(true);
  const [flowInfor, setFlowInfor] = useState<any>(null);
  const [submitCounter, setSubmitCounter] = useState<number>(0)

  useEffect(() => {
    const fetchWorkItems = async () => {
        try {
            const storedToken = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
            const response = await axios.post(authConfig.backEndApiHost + 'workflow/start.php?action=GetNextApprovalUsers', { FlowId, processid: flowRecord.processid, runid: flowRecord.id }, {
                headers: {
                  Authorization: storedToken,
                  'Content-Type': 'application/json'
                }
              });
            const data = response.data;
            console.log("handleToNextStep data", data)  
        } 
        catch (err) {
        } 
        finally {
        } 
    };

    FlowId && FlowId != undefined && fetchWorkItems();

  }, [FlowId]);

  console.log("flowInfor", flowInfor)

  const handleSaveData = () => {
    setSubmitCounter(submitCounter+1)
    console.log("handleSaveData", "handleSaveData")    
  }

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
        <Box sx={{ display: 'flex', flexDirection: 'column' }}>
        </Box>
      )}
    </Fragment>
  );
};

export default GetNextApprovalUsers;
