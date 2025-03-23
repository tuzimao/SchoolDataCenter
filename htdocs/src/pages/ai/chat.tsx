
import { useState, useEffect, Fragment, ReactNode } from 'react'
import axios from 'axios'
import ChatIndex from 'src/views/AiChat/ChatIndex'
import { authConfig, defaultConfig } from 'src/configs/auth'
import CircularProgress from '@mui/material/CircularProgress'
import Box from '@mui/material/Box'
import Grid from '@mui/material/Grid'
import Typography from '@mui/material/Typography'
import BlankLayout from 'src/@core/layouts/BlankLayout'

const ChatIndexAPP = () => {
    const [historyCounter, setHistoryCounter] = useState<number>(0)

    const [pageModel, setPageModel] = useState<string>('')
    const [app, setApp] = useState<any>(null)
    const [loading, setLoading] = useState(false);

    const AiChatId = 1
    const wholePage = true

    const fetchData = async () => {
        setLoading(true);
        try {
          const storedToken = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
          const response = await axios.post(authConfig.backEndApiHost + 'aichat/chatapp.php?action=getApp', { id: AiChatId }, {
            headers: {
              Authorization: storedToken,
              'Content-Type': 'application/json'
            }
          });
          const data = response.data;
          setApp(data.data);
          setPageModel("main")
        } catch (error) {
          console.error('Error fetching data:', error);
        } finally {
          setLoading(false);
        }
      };
    
      useEffect(() => {
        fetchData();
      }, []);
    
    return (
        <Fragment>
            {pageModel == "main" && <ChatIndex authConfig={authConfig} app={app} historyCounter={historyCounter} setHistoryCounter={setHistoryCounter} setPageModel={setPageModel} wholePage={wholePage} /> }
            
            {loading && (
                <Grid item xs={12} sm={12} container justifyContent="space-around">
                    <Box sx={{ mt: 6, mb: 6, display: 'flex', alignItems: 'center', flexDirection: 'column' }}>
                        <CircularProgress size={45} />
                        <Typography sx={{ mt: 6, mb: 6 }}>加载中...</Typography>
                    </Box>
                </Grid>
                )}
        </Fragment>
    )
}

ChatIndexAPP.getLayout = (page: ReactNode) => <BlankLayout>{page}</BlankLayout>

export default ChatIndexAPP