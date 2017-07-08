using Microsoft.Win32;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Diagnostics;
using System.Drawing;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace installer
{
    public partial class Form1 : Form
    {
        public Thread installProc;
        public string log;
        public int endFlag = 0; //0 wip; 1 failed; 2 success;

        public string installPath;

        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (cancelBtn.Text == "&Close")
                Environment.Exit(0);
            else
            {
                DialogResult r = MessageBox.Show("The setup has not yet been completed, if you cancel now the cash register will not function properly.", "Are you sure?", MessageBoxButtons.YesNoCancel, MessageBoxIcon.Exclamation);

                if (r == DialogResult.Yes)
                    Environment.Exit(0);
            }
        }

        private void Form1_FormClosing(object sender, FormClosingEventArgs e)
        {
            e.Cancel = true;
            cancelBtn.PerformClick();
        }

        private void nextBtn_Click(object sender, EventArgs e)
        {
            if (nextBtn.Text == "&Install >")
            {
                createShortcut.Visible = false;
                cancelBtn.Enabled = false;
                closeBtn.Enabled = false;
                nextBtn.Enabled = false;
                installPathLabel.Visible = false;
                installPathTxt.Visible = false;
                startupChk.Visible = false;

                installLog.Visible = true;

                logUpdater.Start();

                installPath = installPathTxt.Text;

                installProc = new Thread(new ThreadStart(installWork));
                installProc.Start();
            }
            else if (nextBtn.Text == "&Finish >")
            {
                createShortcut.Visible = false;
                cancelBtn.Enabled = true;
                closeBtn.Enabled = false;
                installPathLabel.Visible = false;
                installPathTxt.Visible = false;
                installLog.Visible = false;

                doneLabel.Visible = true;
                nextBtn.Enabled = false;
                cancelBtn.Enabled = true;
                cancelBtn.Text = "&Close";
            }
            else
            {
                createShortcut.Visible = false;
                installPathLabel.Visible = true;
                installPathTxt.Visible = true;
                startupChk.Visible = false;

                nextBtn.Text = "&Install >";
            }
        }

        public void installWork()
        {
            string s = @"Windows Registry Editor Version 5.00" + Environment.NewLine +
                        "" + Environment.NewLine +
                        "[HKEY_CLASSES_ROOT\\printhelp]" + Environment.NewLine +
                        "\"Content Type\"=\"text/plain\"" + Environment.NewLine +
                        "\"URL Protocol\"=\"\"" + Environment.NewLine +
                        "" + Environment.NewLine +
                        "[HKEY_CLASSES_ROOT\\printhelp\\shell]" + Environment.NewLine +
                        "" + Environment.NewLine +
                        "[HKEY_CLASSES_ROOT\\printhelp\\shell\\open]" + Environment.NewLine +
                        "" + Environment.NewLine +
                        "[HKEY_CLASSES_ROOT\\printhelp\\shell\\open\\command]" + Environment.NewLine +
                        "@=\"" + installPath.Replace("\\", "\\\\").Replace("\"", "\\\\\"") + "\\\\cli.exe" + " %1\"";

            log += "Registering custom URL protocol..." + Environment.NewLine;
            File.WriteAllText(Environment.CurrentDirectory + "/url_protocol.reg", s);

            try
            {
                Process regeditProcess = Process.Start("regedit.exe", "/s url_protocol.reg");
                regeditProcess.WaitForExit();
            }
            catch (Exception ex)
            {
                log += "Error registering custom URL protocol!!" + Environment.NewLine;
                log += "Installation failed, are we running as administrator?" + Environment.NewLine;

                endFlag = 1;
                return;
            }

            File.Delete(Environment.CurrentDirectory + "/url_protocol.reg");

            log += "Creating directories..." + Environment.NewLine;

            Directory.CreateDirectory(installPath);

            log += "Copying files..." + Environment.NewLine;

            try
            {
                log += installPath + "\\cli.exe" + Environment.NewLine;
                File.WriteAllBytes(installPath + "\\cli.exe", Properties.Resources.cli);

                log += installPath + "\\ui.exe" + Environment.NewLine;
                File.WriteAllBytes(installPath + "\\ui.exe", Properties.Resources.ui);

                //log += installPath + "\\NDde.dll" + Environment.NewLine;
                //File.WriteAllBytes(installPath + "\\NDde.dll", Properties.Resources.NDde);

                log += installPath + "\\Newtonsoft.Json.dll" + Environment.NewLine;
                File.WriteAllBytes(installPath + "\\Newtonsoft.Json.dll", Properties.Resources.Newtonsoft_Json);

                //NEW
                log += installPath + "\\libeay32.dll" + Environment.NewLine;
                File.WriteAllBytes(installPath + "\\libeay32.dll", Properties.Resources.libeay32);

                log += installPath + "\\libgcc_s_dw2-1.dll" + Environment.NewLine;
                File.WriteAllBytes(installPath + "\\libgcc_s_dw2-1.dll", Properties.Resources.libgcc_s_dw2_1);

                log += installPath + "\\libstdc++-6.dll" + Environment.NewLine;
                File.WriteAllBytes(installPath + "\\libstdc++-6.dll", Properties.Resources.libstdc___6);

                log += installPath + "\\libwinpthread-1.dll" + Environment.NewLine;
                File.WriteAllBytes(installPath + "\\libwinpthread-1.dll", Properties.Resources.libwinpthread_1);

                log += installPath + "\\PrintHtml.exe" + Environment.NewLine;
                File.WriteAllBytes(installPath + "\\PrintHtml.exe", Properties.Resources.PrintHtml);

                log += installPath + "\\QtCore4.dll" + Environment.NewLine;
                File.WriteAllBytes(installPath + "\\QtCore4.dll", Properties.Resources.QtCore4);

                log += installPath + "\\QtGui4.dll" + Environment.NewLine;
                File.WriteAllBytes(installPath + "\\QtGui4.dll", Properties.Resources.QtGui4);

                log += installPath + "\\QtNetwork4.dll" + Environment.NewLine;
                File.WriteAllBytes(installPath + "\\QtNetwork4.dll", Properties.Resources.QtNetwork4);

                log += installPath + "\\QtWebKit4.dll" + Environment.NewLine;
                File.WriteAllBytes(installPath + "\\QtWebKit4.dll", Properties.Resources.QtWebKit4);

                log += installPath + "\\ssleay32.dll" + Environment.NewLine;
                File.WriteAllBytes(installPath + "\\ssleay32.dll", Properties.Resources.ssleay32);

                log += installPath + "\\ca-bundle.crt" + Environment.NewLine;
                File.WriteAllBytes(installPath + "\\ca-bundle.crt", Properties.Resources.ca_bundle);
                //END

                log += installPath + "\\uninstall.bat" + Environment.NewLine;
                File.WriteAllBytes(installPath + "\\uninstall.exe", Properties.Resources.uninstall);
            }
            catch (Exception ex)
            {
                log += "Error copying files!!" + Environment.NewLine;
                log += "Installation failed, are we running as administrator?" + Environment.NewLine;

                endFlag = 1;
                return;
            }

            if (startupChk.Checked)
            {
                string ss = @"Windows Registry Editor Version 5.00" + Environment.NewLine +
                            "[HKEY_CURRENT_USER\\Software\\Microsoft\\Windows\\CurrentVersion\\Run]" + Environment.NewLine +
                             "\"CashRegister Printer Helper\"=\"\\\"" + installPath.Replace("\\", "\\\\").Replace("\"", "\\\\\"") + "\\\\ui.exe" + "\\\"\"";

                log += "Adding program to startup list..." + Environment.NewLine;
                File.WriteAllText(Environment.CurrentDirectory + "/startup.reg", ss);

                try
                { 
                    Process regeditProcess = Process.Start("regedit.exe", "/s startup.reg");
                    regeditProcess.WaitForExit();
                }
                catch (Exception ex)
                {
                    log += "Error registering to startup list!!" + Environment.NewLine;
                    log += "Installation failed, are we running as administrator?" + Environment.NewLine;

                    endFlag = 1;
                    return;
                }

                File.Delete(Environment.CurrentDirectory + "/startup.reg");
            }

            if (createShortcut.Checked)
            {
                log += "Creating shortcut on desktop..." + Environment.NewLine;
                try
                { 
                    appShortcutToDesktop("Kassa printer helper");
                }
                catch (Exception ex)
                {
                    log += "Error creating shortcut!!" + Environment.NewLine;
                    log += "Installation failed, are we running as administrator?" + Environment.NewLine;

                    endFlag = 1;
                    return;
                }
            }

            log += "Creating uninstaller..." + Environment.NewLine;

            CreateUninstaller();

            log += "Installation finished!" + Environment.NewLine;
            endFlag = 2;
        }

        private void CreateUninstaller()
        {
            using (RegistryKey parent = Registry.LocalMachine.OpenSubKey(
                         @"SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall", true))
            {
                if (parent == null)
                {
                    throw new Exception("Uninstall registry key not found.");
                }
                try
                {
                    RegistryKey key = null;

                    try
                    {
                        string guidText = "CashRegPrintHelper";
                        key = parent.OpenSubKey(guidText, true) ??
                              parent.CreateSubKey(guidText);

                        if (key == null)
                        {
                            throw new Exception(String.Format("Unable to create uninstaller '{0}\\{1}'", installPath.Replace("\\", "\\\\").Replace("\"", "\\\\\"") + "\\uninstall.exe", guidText));
                        }

                        key.SetValue("DisplayName", "Cash Register Print Helper");
                        key.SetValue("ApplicationVersion", "1.0.0");
                        key.SetValue("Publisher", "M.C. van Leeuwen");
                        key.SetValue("DisplayIcon", installPath.Replace("\\", "\\\\").Replace("\"", "\\\\\"") + "\\uninstall.exe");
                        key.SetValue("DisplayVersion", "1.0.0");
                        key.SetValue("URLInfoAbout", "http://www.stardebris.net");
                        key.SetValue("Contact", "menno.vanleeuwen@stardebris.net");
                        key.SetValue("InstallDate", DateTime.Now.ToString("yyyyMMdd"));
                        key.SetValue("UninstallString", installPath + "\\uninstall.exe");
                    }
                    finally
                    {
                        if (key != null)
                        {
                            key.Close();
                        }
                    }
                }
                catch (Exception ex)
                {
                    log += "An error occurred writing uninstall information to the registry.  The service is fully installed but can only be uninstalled manually through the command line.";
                }
            }
        }

        private void appShortcutToDesktop(string linkName)
        {
            string deskDir = Environment.GetFolderPath(Environment.SpecialFolder.DesktopDirectory);

            using (StreamWriter writer = new StreamWriter(deskDir + "\\" + linkName + ".url"))
            {
                string app = installPath + "\\ui.exe";
                writer.WriteLine("[InternetShortcut]");
                writer.WriteLine("URL=file:///" + app);
                writer.WriteLine("IconIndex=0");
                string icon = app.Replace('\\', '/');
                writer.WriteLine("IconFile=" + icon);
                writer.Flush();
            }
        }

        private void logUpdater_Tick(object sender, EventArgs e)
        {
            installLog.Text = log;

            if (installLog.Visible)
            {
                installLog.SelectionStart = installLog.TextLength;
                installLog.ScrollToCaret();
            }

            if (endFlag > 0)
            {
                cancelBtn.Enabled = false;
                closeBtn.Enabled = false;
                nextBtn.Enabled = true;

                nextBtn.Text = "&Finish >";

                logUpdater.Stop();
            }
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            installPathTxt.Text = Environment.GetFolderPath(Environment.SpecialFolder.ProgramFilesX86) + "\\" + "M.C. van Leeuwen" + "\\" + "CR_PrintHelper";
        }
    }
}
