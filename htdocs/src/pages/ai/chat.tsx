
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

    const QuestionGuideTemplate = '你是一个AI智能助手，可以回答和解决我的问题。请结合前面的对话记录，以用户自己的角度，帮用户生成 3 个问题，用于引导用户来进行继续提问。要求提问的角度要站在用户的角度生成提问句子。问题的长度应小于20个字符，要求使用UTF-8编码，按 JSON 格式返回: ["问题1", "问题2", "问题3"]'

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
          setApp({...data.data, id: "ChatApp-" + data.data.id, AppName2: data.data.AppModel, avatar: '1.png', Model: {}, QuestionGuideTemplate })
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