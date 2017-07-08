/*
 * Created by SharpDevelop.
 * User: StarDebris
 * Date: 6/17/2017
 * Time: 12:01 PM
 * 
 * To change this template use Tools | Options | Coding | Edit Standard Headers.
 */
using System;
using System.Collections.Generic;
using System.Drawing;
using System.Windows.Forms;
using System.Management;
using System.Runtime.InteropServices;
using Newtonsoft.Json;
using System.IO;
using System.Threading;
using System.Diagnostics;
using System.Net;
using System.Drawing.Printing;

namespace CashRegister_PrintHelper
{
	/// <summary>
	/// Description of MainForm.
	/// </summary>
	public partial class MainForm : Form
	{
		[DllImport("winspool.drv", 
			              CharSet = CharSet.Auto, 
			              SetLastError = true)]
	    [return: MarshalAs(UnmanagedType.Bool)]
	    public static extern Boolean SetDefaultPrinter(String name);
	   	
	    public Settings prefs = new Settings();
	    
		public MainForm()
		{
			InitializeComponent();
			
			if (File.Exists(Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks/settings.json"))
				prefs = JsonConvert.DeserializeObject<Settings>(File.ReadAllText(Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks/settings.json"));
			
			var printerQuery = new ManagementObjectSearcher("SELECT * from Win32_Printer");
			foreach (var printer in printerQuery.Get())
			{
				string name = printer.GetPropertyValue("Name").ToString();
			    string isDefault = printer.GetPropertyValue("Default").ToString();
			    
			    if (isDefault == "true" || prefs.defaultPrinter == name)
			    	comboBox1.Text = name;
	
			    comboBox1.Items.Add(name);
			}
						
			foreach (var printer in printerQuery.Get())
			{
				string name = printer.GetPropertyValue("Name").ToString();
			    string status = printer.GetPropertyValue("Status").ToString();
			    
			    string isDefault = printer.GetPropertyValue("Default").ToString();
			    string isNetworkPrinter = printer.GetPropertyValue("Network").ToString();
			    
			    if (isDefault == "true")
			    	isDefault = "Ja";
			    else if (name == prefs.defaultPrinter)
			    	isDefault = "Ja";
			    else 
			    	isDefault = "Nee";
			    
			    if (isNetworkPrinter == "true")
			    	isNetworkPrinter = "Ja";
			    else 
			    	isNetworkPrinter = "Nee";
			    
			    if (comboBox1.Text == name)
			    {
			    	printerInfo.Text = name + "\nStatus: " + status + "\nIs standaard: " + isDefault + "\nIs netwerk printer: " + isNetworkPrinter;
			    	return;
			    }
			}
		}
		
		void AfsluitenToolStripMenuItemClick(object sender, EventArgs e)
		{
			DialogResult res = MessageBox.Show("Weet je zeker dat je de printer helper wilt sluiten?\nAls je de printer helper sluit kan je geen bonnen meer printen op de kassa!", "Weet je het zeker?", MessageBoxButtons.YesNo, MessageBoxIcon.Exclamation);

            if (res == DialogResult.Yes)
            {
                printHelperTray.Visible = false;
                Environment.Exit(-1);
            }
		}
		
		void HideFormBtnClick(object sender, EventArgs e)
		{
			this.Hide();
		}
		
		void SaveBtnClick(object sender, EventArgs e)
		{		
			SetDefaultPrinter(comboBox1.Text);
			prefs.defaultPrinter = comboBox1.Text;
			
			string output = JsonConvert.SerializeObject(prefs);
			File.WriteAllText(Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks/settings.json", output);
			
			comboBox1.Items.Clear();
			comboBox1.Text = "";
			printerInfo.Text = "";
			
			var printerQuery = new ManagementObjectSearcher("SELECT * from Win32_Printer");
			foreach (var printer in printerQuery.Get())
			{
				string name = printer.GetPropertyValue("Name").ToString();
			    string isDefault = printer.GetPropertyValue("Default").ToString();
			    
			    if (isDefault == "true" || prefs.defaultPrinter == name)
			    	comboBox1.Text = name;
	
			    comboBox1.Items.Add(name);
			}
						
			foreach (var printer in printerQuery.Get())
			{
				string name = printer.GetPropertyValue("Name").ToString();
			    string status = printer.GetPropertyValue("Status").ToString();
			    
			    string isDefault = printer.GetPropertyValue("Default").ToString();
			    string isNetworkPrinter = printer.GetPropertyValue("Network").ToString();
			    
			    if (isDefault == "true")
			    	isDefault = "Ja";
			    else if (name == prefs.defaultPrinter)
			    	isDefault = "Ja";
			    else 
			    	isDefault = "Nee";
			    
			    if (isNetworkPrinter == "true")
			    	isNetworkPrinter = "Ja";
			    else 
			    	isNetworkPrinter = "Nee";
			    
			    if (comboBox1.Text == name)
			    {
			    	printerInfo.Text = name + "\nStatus: " + status + "\nIs standaard: " + isDefault + "\nIs netwerk printer: " + isNetworkPrinter;
			    	return;
			    }
			}
		}
		
		void PrintHelperTrayDoubleClick(object sender, EventArgs e)
		{
			this.Show();
		}
		
		void InstellingenToolStripMenuItemClick(object sender, EventArgs e)
		{
			this.Show();
		}
		
		void StartupTick(object sender, EventArgs e)
		{
			this.Hide();
			startup.Stop();
		}
		
		void ComboBox1SelectedIndexChanged(object sender, EventArgs e)
		{
			var printerQuery = new ManagementObjectSearcher("SELECT * from Win32_Printer");
			foreach (var printer in printerQuery.Get())
			{
				string name = printer.GetPropertyValue("Name").ToString();
			    string status = printer.GetPropertyValue("Status").ToString();
			    
			    string isDefault = printer.GetPropertyValue("Default").ToString();
			    string isNetworkPrinter = printer.GetPropertyValue("Network").ToString();
			    
			    if (isDefault == "true")
			    	isDefault = "Ja";
			    else 
			    	isDefault = "Nee";
			    
			    if (isNetworkPrinter == "true")
			    	isNetworkPrinter = "Ja";
			    else 
			    	isNetworkPrinter = "Nee";
			    
			    if (comboBox1.Text == name)
			    {
			    	printerInfo.Text = name + "\nStatus: " + status + "\nIs standaard: " + isDefault + "\nIs netwerk printer: " + isNetworkPrinter;
			    	return;
			    }
			}
		}
		void MainFormFormClosing(object sender, FormClosingEventArgs e)
		{
			e.Cancel = true;
			this.Hide();
		}
		
		void StopBtnClick(object sender, EventArgs e)
		{
			afsluitenToolStripMenuItem.PerformClick();
		}
		
		void Button1Click(object sender, EventArgs e)
		{
			printHelperTray.ShowBalloonTip(1000, "Print taak", "Uw print taak is verwerkt naar de printer.", ToolTipIcon.Info);
		}
		
		public void downloadFileToTemp(string url, string fileName)
		{
			using (var client = new WebClient())
			{
				if (!Directory.Exists(Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks/temp"))
					Directory.CreateDirectory(Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks/temp");
				
			    client.DownloadFile(url, Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks/temp/" + fileName);
			}
		}
		
		public void printFile(string path)
		{
		 	ProcessStartInfo info = new ProcessStartInfo();
	        info.Verb = "print";
	        info.FileName =path;
	        info.CreateNoWindow = true;
	        info.WindowStyle = ProcessWindowStyle.Hidden;
	
	        Process p = new Process();
	        p.StartInfo = info;
	        p.Start();
		}

        List<string> busy = new List<string>();
        List<string> uibusy = new List<string>();

		void UrlHandlerTick(object sender, EventArgs e)
		{
            if (!Directory.Exists(Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks"))
                Directory.CreateDirectory(Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks");

            //Check the task folder for new tasks
            String[] allfiles = Directory.GetFiles(Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks", "*.*", System.IO.SearchOption.TopDirectoryOnly);
            List<string> stillBusy = new List<string>();

            foreach (var file in allfiles)
            {
                FileInfo info = new FileInfo(file);
                if (!busy.Contains(info.FullName))
                {
                    if (info.Extension == ".lock")
                    {
                        stillBusy.Add(info.FullName);
                        printHelperTray.ShowBalloonTip(1000, "Printer taak", "Printer taak wordt verwerkt...", ToolTipIcon.Info);
                        prefs.printLog.Add(new Log("Catched new print task | " + info.FullName));
                        prefs.Save();
                    }
                    else if (info.Extension == ".html")
                    {
                        if (!uibusy.Contains(info.FullName))
                        {
                            uibusy.Add(info.FullName);

                            string id = info.Name.Split('-')[0];
                            short count = 1;

                            prefs.printLog.Add(new Log("Print task sent to printer | " + info.FullName + ".pdf"));
                            prefs.Save();

                            try
                            {
                                Console.WriteLine(Environment.CurrentDirectory + "\\PrintHtml.exe" + " -t 0 -b 0 -p \"" + prefs.defaultPrinter + "\" \"" + info.FullName + "\"");
                                var process = Process.Start(Environment.CurrentDirectory + "\\PrintHtml.exe", " -t 0 -b 0 -p \"" + prefs.defaultPrinter + "\" \"" + info.FullName + "\"");
                                process.WaitForExit();

                                if (process.ExitCode == 0)
                                {

                                    prefs.printLog.Add(new Log("Finished print task | " + info.FullName));
                                    prefs.Save();
                                }
                                else
                                {
                                    printHelperTray.ShowBalloonTip(5000, "Printer taak", "Printer taak mislukt, zie print helper log bestand for details", ToolTipIcon.Info);
                                    prefs.printLog.Add(new Log("Printer task failed, something went wrong PrintHtml.exe ended with code " + process.ExitCode + " | " + info.FullName));
                                    prefs.Save();
                                }
                            }
                            catch (Exception ex)
                            {
                                printHelperTray.ShowBalloonTip(5000, "Printer taak", "Printer taak mislukt, zie print helper log bestand for details", ToolTipIcon.Info);
                                prefs.printLog.Add(new Log("Printer task failed, PrintHtml is missing? | " + info.FullName + " | " + ex.Message));
                                prefs.Save();
                            }

                            uibusy.Remove(info.FullName);
                            File.Delete(info.FullName);
                            File.Delete(info.DirectoryName + "/" + info.Name + ".lock");
                        }
                    }
                }
                else
                {
                    stillBusy.Add(info.FullName);
                }
            }

            busy = stillBusy;

            activeTasks.Items.Clear();
            foreach (String s in uibusy)
            {
                FileInfo info = new FileInfo(s);
                activeTasks.Items.Add(info.Name.Replace(".html", "") + " naar " + prefs.defaultPrinter + " (Status: Verstuurt naar printer)");
            }

            foreach (String s in busy)
            {
                FileInfo info = new FileInfo(s);

                try
                {
                    string name = info.Name.Replace(".lock", "");
                    activeTasks.Items.Add(name + " naar " + prefs.defaultPrinter + " (Status: " + File.ReadAllText(info.FullName) + ")");
                }
                catch (Exception ex) { }
            }
        }

        private void button1_Click(object sender, EventArgs e)
        {
            logViewer log = new logViewer(prefs);
            log.Show();
        }

        private void MainForm_Load(object sender, EventArgs e)
        {

        }
    }
}
