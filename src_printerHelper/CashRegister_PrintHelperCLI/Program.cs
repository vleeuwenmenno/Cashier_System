using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Net;
using System.IO;
using System.ComponentModel;
using System.Web;

namespace CashRegister_PrintHelperCLI
{
    class Program
    {
        public static WebClient webClient;
        public static string filename;
        public static bool downloading = true;
        public static string loc;

        static void Main(string[] args)
        {
            if (args.Length < 1)
            {
                Console.WriteLine("Usage is: printhelper <URL>");
                Environment.Exit(100);
            }
            else
            {
                string url = HttpUtility.UrlDecode(args[0]).Replace("printhelp://", "");

                url = url.Substring(0, url.Length - 1); //get rid of the ending slash

                //Check if URL is valid
                bool exists;

                try
                {
                    HttpWebRequest request = (HttpWebRequest)HttpWebRequest.Create(url); //http%3A%2F%2F10.0.0.164%2FCashRegister%2Fsrc%2Ftemp%2F1182794729.pdf
                    request.Method = "HEAD";

                    request.GetResponse();
                    exists = true;
                }
                catch
                {
                    exists = false;
                }

                if (exists)
                {
                    //Make a lock file in CWD/print_helper_tasks
                    if (!Directory.Exists(Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks"))
                        Directory.CreateDirectory(Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks");

                    filename = url.Substring(url.LastIndexOf("/") + 1);
                    File.WriteAllText((Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks/" + filename + ".lock"), "Bezig met bestand ophalen...0%");

                    //Download file to CWD/print_helper_tasks
                    DownloadFile(url, Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks/" + filename + ".down");

                    while (downloading) ;
                }
                else
                {
                    Console.WriteLine(url + " doesn't seem to be a valid URL");
                    Environment.Exit(100);
                }
            }
        }

        static void DownloadFile(string urlAddress, string location)
        {
            loc = location;

            using (webClient = new WebClient())
            {
                webClient.DownloadFileCompleted += new AsyncCompletedEventHandler(Completed);
                webClient.DownloadProgressChanged += new DownloadProgressChangedEventHandler(ProgressChanged);

                // The variable that will be holding the url address (making sure it starts with http://)
                Uri URL = urlAddress.StartsWith("http://", StringComparison.OrdinalIgnoreCase) ? new Uri(urlAddress) : new Uri("http://" + urlAddress);

                try
                {
                    // Start downloading the file
                    webClient.DownloadFileAsync(URL, location);
                }
                catch (Exception ex)
                {
                    Console.WriteLine(ex.Message);
                }

                webClient.Dispose();
            }
        }

        private static void ProgressChanged(object sender, DownloadProgressChangedEventArgs e)
        {
            // perc: e.ProgressPercentage;
            try
            {
                File.WriteAllText((Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks/" + filename + ".lock"), "Bezig met bestand ophalen..." + e.ProgressPercentage.ToString() + "%");
            }
            catch (Exception ex) { }
        }

        // The event that will trigger when the WebClient is completed
        private static void Completed(object sender, AsyncCompletedEventArgs e)
        {
            if (e.Cancelled == true)
            {
                bool notWritten = true;
                while (notWritten)
                {
                    try
                    {
                        File.WriteAllText((Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks/" + filename + ".lock"), "Bestand ophalen is mislukt");
                        notWritten = false;
                    }
                    catch (Exception ex) { notWritten = true; }
                }

                downloading = false;
            }
            else
            {
                bool notWritten = true;
                while (notWritten)
                {
                    try
                    {
                        File.WriteAllText((Path.Combine(Environment.ExpandEnvironmentVariables("%userprofile%"), "Documents") + "/print_helper_tasks/" + filename + ".lock"), "Bestand opgehaald");
                        notWritten = false;
                    }
                    catch (Exception ex) { notWritten = true; }
                }

                //Renaming file to .pdf so the handler can take over
                File.Move(loc, loc.Replace(".down", ""));
                downloading = false;
            }
        }
    }
}
